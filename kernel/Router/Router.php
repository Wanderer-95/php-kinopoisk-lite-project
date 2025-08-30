<?php

namespace Kernel\Router;

use Kernel\Auth\AuthInterface;
use Kernel\Database\DatabaseInterface;
use Kernel\Http\Redirect\RedirectInterface;
use Kernel\Http\Request\RequestInterface;
use Kernel\Middleware\AbstractMiddleware;
use Kernel\Session\SessionInterface;
use Kernel\View\ViewInterface;

class Router implements RouterInterface
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct(
        private ViewInterface $view,
        private RequestInterface $request,
        private SessionInterface $session,
        private RedirectInterface $redirect,
        private DatabaseInterface $database,
        private AuthInterface $auth
    ) {
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

            if ($route->hasMiddlewares()) {
                /**
                 * @var AbstractMiddleware $middleware
                 */
                foreach ($route->getMiddlewares() as $middlewareClass) {
                    $middleware = new $middlewareClass(
                        $this->request,
                        $this->auth,
                        $this->redirect
                    );
                    $middleware->handle();
                }
            }
            $controller = new $controller;
            $handler = [$controller, $action];
            call_user_func_array([$controller, 'setRedirect'], [$this->redirect]);
            call_user_func_array([$controller, 'setSession'], [$this->session]);
            call_user_func_array([$controller, 'setView'], [$this->view]);
            call_user_func_array([$controller, 'setRequest'], [$this->request]);
            call_user_func_array([$controller, 'setDatabase'], [$this->database]);
            call_user_func_array([$controller, 'setAuth'], [$this->auth]);
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
