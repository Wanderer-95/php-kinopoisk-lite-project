<?php

namespace Kernel\Container;

use Kernel\Http\Redirect\Redirect;
use Kernel\Http\Request\Request;
use Kernel\Router\Router;
use Kernel\Session\Session;
use Kernel\Validator\Validator;
use Kernel\View\View;

class Container
{
    private Router $router;

    private Request $request;

    private View $view;

    private Validator $validator;

    private Redirect $redirect;

    private Session $session;

    public function __construct(
    ) {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->session = new Session();
        $this->redirect = new Redirect();
        $this->request = Request::createFromGlobals();
        $this->view = new View($this->session);
        $this->router = new Router($this->view, $this->request, $this->session, $this->redirect);
        $this->validator = new Validator();
        $this->request->setValidator($this->validator);
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getView(): View
    {
        return $this->view;
    }
}
