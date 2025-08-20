<?php

namespace Kernel\Controller;

use Kernel\Database\DatabaseInterface;
use Kernel\Http\Redirect\Redirect;
use Kernel\Http\Redirect\RedirectInterface;
use Kernel\Http\Request\Request;
use Kernel\Http\Request\RequestInterface;
use Kernel\Session\Session;
use Kernel\Session\SessionInterface;
use Kernel\View\View;
use Kernel\View\ViewInterface;

abstract class Controller
{
    private ViewInterface $view;
    private RequestInterface $request;
    private RedirectInterface $redirect;
    private SessionInterface $session;
    private DatabaseInterface $database;

    public function view(string $name): void
    {
        $this->view->render($name);
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

    /**
     * @param RedirectInterface $redirect
     */
    public function setRedirect(RedirectInterface $redirect): void
    {
        $this->redirect = $redirect;
    }

    /**
     * @return RedirectInterface
     */
    public function getRedirect(): RedirectInterface
    {
        return $this->redirect;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @return DatabaseInterface
     */
    public function db(): DatabaseInterface
    {
        return $this->database;
    }

    /**
     * @param DatabaseInterface $database
     */
    public function setDatabase(DatabaseInterface $database): void
    {
        $this->database = $database;
    }
}
