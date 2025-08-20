<?php

namespace Kernel\Http\Request;

use Kernel\Validator\ValidatorInterface;

interface RequestInterface
{
    public static function createFromGlobals(): static;
    public function uri(): string;
    public function method(): string;
    public function input(string $key, mixed $default = null): mixed;
    public function postAll(): array;
    public function setValidator(ValidatorInterface $validator): void;
    public function validate(array $data, array $rules): bool;
    public function errors(): array;
}