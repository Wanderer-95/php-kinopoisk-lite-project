<?php

namespace Kernel\Container;

use Kernel\Http\Request\Request;
use Kernel\Router\Router;
use Kernel\View\View;

class Container
{
    private Router $router;
    private Request $request;
    private View $view;

    public function __construct(
    )
    {
        $this->registerServices();
    }

    private function registerServices(): void
    {
        $this->view = new View();
        $this->router = new Router($this->view);
        $this->request = Request::createFromGlobals();
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return View
     */
    public function getView(): View
    {
        return $this->view;
    }
}