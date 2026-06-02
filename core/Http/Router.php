<?php

declare(strict_types=1);

namespace Core\Http;

use Core\Container\Container;
 
final class Router{ 
    private array $routes = [];

    public function get(string $pattern, array $handler): void{
        $this->add(HttpMethod::GET, $pattern, $handler);
    }

    public function post(string $pattern, array $handler): void{
        $this->add(HttpMethod::POST, $pattern, $handler);
    }

    public function add(HttpMethod $method, string $pattern, array $handler): void {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request, Container $container): Response{
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method) {
                continue;
            }

            $params = $this->match($route['pattern'], $request->uri);
            if ($params === null) {
                continue;
            }

            [$class, $action] = $route['handler'];
            $controller = $container->resolve($class);

            return $controller->{$action}(
                $request->withRouteParameters($params),
            );
        }

        return Response::html('<h1>404 Not Found</h1><p>No route for ' . htmlspecialchars($request->uri) . '</p>', 404);
    }

    private function match(string $pattern, string $uri): ?array{
        $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $uri, $matches)) {
            return null;
        }

        $params = [];
        foreach ($matches as $key => $value) {
            if (!is_int($key)) {
                $params[$key] = $value;
            }
        }

        return $params;
    }
}
