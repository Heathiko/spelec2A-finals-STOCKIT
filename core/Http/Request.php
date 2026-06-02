<?php

declare(strict_types=1);

namespace Core\Http;


final class Request{
    public function __construct(
        public readonly HttpMethod $method,
        public readonly string $uri,
        public readonly array $query = [],
        public readonly array $post = [],
        public readonly array $routeParams = [],
    ){}

    public static function capture(): self
    {
        $method = HttpMethod::tryFrom($_SERVER['REQUEST_METHOD'] ?? 'GET') ?? HttpMethod::GET; // check if the request method is valid, default to GET if not
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $uri = rtrim($uri, '/') ?: '/';

        return new self(
            method: $method,
            uri: $uri,
            query: $_GET,
            post: $_POST,
        );
    }


    public function withRouteParameters(array $routeParams): self{
        return new self(
            method: $this->method,
            uri: $this->uri,
            query: $this->query,
            post: $this->post,
            routeParams: $routeParams,
        );
    }


    public function input(string $key, string|int|float|null $default = null): string|int|float|null{
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    public function route(string $key, string|int|null $default = null): string|int|null{
        $value = $this->routeParams[$key] ?? $default;

        return is_numeric($value) ? (int) $value : $value;
    }

 
}