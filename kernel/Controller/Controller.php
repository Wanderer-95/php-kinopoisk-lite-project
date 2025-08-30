<?php

namespace Kernel\Controller;

use Kernel\Auth\AuthInterface;
use Kernel\Database\DatabaseInterface;
use Kernel\Http\Redirect\RedirectInterface;
use Kernel\Http\Request\RequestInterface;
use Kernel\Session\SessionInterface;
use Kernel\View\ViewInterface;

abstract class Controller
{
    private ViewInterface $view;

    private RequestInterface $request;

    private RedirectInterface $redirect;

    private SessionInterface $session;

    private DatabaseInterface $database;

    private AuthInterface $auth;

    public function view(string $name, array $data = []): void
    {
        $this->view->render($name, $data);
    }

    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function setRedirect(RedirectInterface $redirect): void
    {
        $this->redirect = $redirect;
    }

    public function getRedirect(): RedirectInterface
    {
        return $this->redirect;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function db(): DatabaseInterface
    {
        return $this->database;
    }

    public function setDatabase(DatabaseInterface $database): void
    {
        $this->database = $database;
    }

    public function auth(): AuthInterface
    {
        return $this->auth;
    }

    public function setAuth(AuthInterface $auth): void
    {
        $this->auth = $auth;
    }
}
