<?php

namespace Kernel\Auth;

interface AuthInterface
{
    public function attempt(string $email, string $pass): bool;

    public function logout(): void;

    public function check(): bool;

    public function user(): ?User;

    public function getSessionUserField(): string;

    public function getTable(): string;

    public function getUsername(): string;
}
