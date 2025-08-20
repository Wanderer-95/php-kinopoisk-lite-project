<?php

namespace Kernel\Session;

class Session implements SessionInterface
{
    public function __construct()
    {
        session_start();
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        $flash = $this->get($key, $default);
        $this->remove($key);

        return $flash;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $_SESSION);
    }
}