<?php

declare(strict_types=1);

namespace Core\Container;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

final class Container{ 
    private array $instances = [];
    private array $bindings = [];
    public function bind(string $abstract, string|callable $concrete, bool $singleton = true): void{
        $this->bindings[$abstract] = $concrete;

        if ($singleton && isset($this->instances[$abstract])) {
            unset($this->instances[$abstract]);
        }
    }
    public function resolve(string $class): object{
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->bindings[$class])) {
            $concrete = $this->bindings[$class];
            $object = is_callable($concrete)
                ? $concrete()
                : ($concrete === $class ? $this->build($class) : $this->resolve($concrete));
            $this->instances[$class] = $object;

            return $object;
        }

        $object = $this->build($class);
        $this->instances[$class] = $object;

        return $object;
    }

    private function build(string $class): object{
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = array_map(
            fn (ReflectionParameter $parameter) => $this->resolveParameter($parameter),
            $constructor->getParameters(),
        );

        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveParameter(ReflectionParameter $parameter): mixed{
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            if ($parameter->isDefaultValueAvailable()) {
                return $parameter->getDefaultValue();
            }

            throw new \RuntimeException("Unable to resolve parameter \${$parameter->getName()}");
        }

        return $this->resolve($type->getName());
    }
}
