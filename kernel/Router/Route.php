<?php

namespace Kernel\Router;

class Route
{
    public function __construct(private string $uri, private string $method, private $callback)
    {}

    public static function get(string $uri, array|callable $handler): Route
    {
        return new static($uri, 'GET', $handler);
    }

    public static function post(string $uri, array|callable $handler): Route
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
}