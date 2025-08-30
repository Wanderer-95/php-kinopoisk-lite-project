<?php

namespace Kernel\Auth;

class User
{
    public function __construct(
        private int $id,
        private string $name,
        private string $email
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
