<?php

namespace Kernel\Container;

use Kernel\Auth\Auth;
use Kernel\Auth\AuthInterface;
use Kernel\Database\Database;
use Kernel\Database\DatabaseInterface;
use Kernel\Http\Redirect\Redirect;
use kernel\Http\Redirect\RedirectInterface;
use Kernel\Http\Request\Request;
use kernel\Http\Request\RequestInterface;
use Kernel\Router\Router;
use kernel\Router\RouterInterface;
use Kernel\Session\Session;
use kernel\Session\SessionInterface;
use Kernel\Validator\Validator;
use kernel\Validator\ValidatorInterface;
use Kernel\View\View;
use kernel\View\ViewInterface;

class Container
{
    private RouterInterface $router;

    private RequestInterface $request;

    private ViewInterface $view;

    private ValidatorInterface $validator;

    private RedirectInterface $redirect;

    private SessionInterface $session;

    private DatabaseInterface $database;

    private AuthInterface $auth;

    public function __construct(
    ) {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->session = new Session;
        $this->database = new Database;
        $this->redirect = new Redirect;
        $this->auth = new Auth($this->database, $this->session);
        $this->request = Request::createFromGlobals();
        $this->view = new View($this->session, $this->auth);
        $this->router = new Router($this->view, $this->request, $this->session, $this->redirect, $this->database, $this->auth);
        $this->validator = new Validator;
        $this->request->setValidator($this->validator);
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getView(): ViewInterface
    {
        return $this->view;
    }
}
