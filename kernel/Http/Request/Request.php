<?php

namespace Kernel\Http\Request;

class Request
{
    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $files,
        private readonly array $server,
        private readonly array $cookies,
    ){}

    public static function createFromGlobals(): static
    {
        return new static(
            $_GET,
            $_POST,
            $_FILES,
            $_SERVER,
            $_COOKIE
        );
    }

    public function uri(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}