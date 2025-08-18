<?php

namespace Kernel\Router;

use Kernel\Http\Redirect\Redirect;
use Kernel\Http\Request\Request;
use Kernel\Session\Session;
use Kernel\View\View;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct(private View $view, private Request $request, private Session $session, private Redirect $redirect)
    {
        $this->initRoutes();
    }

    public function dispatch(string $uri, string $method): void
    {
        $route = $this->findRoute($uri, $method);

        if (! $route) {
            $this->notFoundPage();
        }

        $handler = $route->getCallback();

        if (is_array($route->getCallback())) {
            [$controller, $action] = $route->getCallback();
            $controller = new $controller;
            $handler = [$controller, $action];
            call_user_func_array([$controller, 'setRedirect'], [$this->redirect]);
            call_user_func_array([$controller, 'setSession'], [$this->session]);
            call_user_func_array([$controller, 'setView'], [$this->view]);
            call_user_func_array([$controller, 'setRequest'], [$this->request]);
        }

        call_user_func_array($handler, []);
    }

    private function notFoundPage(): void
    {
        echo '404 | NOT FOUND';
        exit();
    }

    private function findRoute(string $uri, string $method): Route|false
    {
        $route = $this->routes[$method][$uri] ?? null;

        if (is_null($route)) {
            return false;
        }

        return $route;
    }

    private function initRoutes(): void
    {
        $routes = $this->getRoutes();

        foreach ($routes as $route) {
            $this->routes[$route->getMethod()][$route->getUri()] = $route;
        }
    }

    /**
     * @return Route[]
     */
    private function getRoutes(): array
    {
        return require APP_PATH.'/routes/web.php';
    }
}
