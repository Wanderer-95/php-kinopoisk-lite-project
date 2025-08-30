<?php

namespace Kernel\Router;

class Route
{
    public function __construct(private string $uri, private string $method, private $callback, private array $middlewares = []) {}

    public static function get(string $uri, array|callable $handler, array $middlewares = []): Route
    {
        return new static($uri, 'GET', $handler, $middlewares);
    }

    public static function post(string $uri, array|callable $handler, array $middlewares = []): Route
    {
        return new static($uri, 'POST', $handler);
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getCallback(): array|callable
    {
        return $this->callback;
    }

    public function hasMiddlewares(): bool
    {
        return ! empty($this->middlewares);
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
