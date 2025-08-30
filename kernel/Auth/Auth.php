<?php

namespace Kernel\Auth;

use Kernel\Database\DatabaseInterface;
use Kernel\Session\SessionInterface;

class Auth implements AuthInterface
{
    public function __construct(
        private DatabaseInterface $db,
        private SessionInterface $session
    ) {}

    public function attempt(string $email, string $pass): bool
    {
        $table = $this->getTable();
        $username = $this->getUsername();
        $password = config('auth.password');

        $user = $this->db->first($table, [$username => $email]);

        if (! $user) {
            return false;
        }

        if (! password_verify($pass, $user[$password])) {
            return false;
        }

        $this->session->set($this->getSessionUserField(), $user['id']);

        return true;
    }

    public function logout(): void
    {
        $this->session->remove($this->getSessionUserField());
    }

    public function check(): bool
    {
        return $this->session->has($this->getSessionUserField());
    }

    public function user(): ?User
    {
        $user = $this->db->first($this->getTable(), ['id' => $this->session->get($this->getSessionUserField())]);

        if ($user) {
            return new User($user['id'], $user['name'], $user['email']);
        }

        return null;
    }

    public function getSessionUserField(): string
    {
        return config('auth.session_user_field');
    }

    public function getTable(): string
    {
        return config('auth.table');
    }

    public function getUsername(): string
    {
        return config('auth.username');
    }
}
