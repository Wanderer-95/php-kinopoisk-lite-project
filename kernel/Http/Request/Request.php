<?php

namespace Kernel\Http\Request;

use Kernel\Validator\Validator;

class Request
{
    private Validator $validator;

    public function __construct(
        private readonly array $get,
        private readonly array $post,
        private readonly array $files,
        private readonly array $server,
        private readonly array $cookies,
    ) {}

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

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function postAll(): array
    {
        return $this->post;
    }

    public function setValidator(Validator $validator): void
    {
        $this->validator = $validator;
    }

    public function validate(array $data, array $rules): bool
    {
        return $this->validator->validate($data, $rules);
    }

    public function errors(): array
    {
        return $this->validator->errors();
    }
}
