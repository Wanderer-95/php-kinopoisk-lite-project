<?php

namespace Kernel\View;

use Kernel\Auth\AuthInterface;
use Kernel\Exceptions\ViewNotFoundException;
use Kernel\Session\SessionInterface;

class View implements ViewInterface
{
    public function __construct(
        private SessionInterface $session,
        private AuthInterface $auth
    ) {}

    public function render(string $name, array $data = []): void
    {
        $viewPath = APP_PATH.'/views/pages/'.$name.'.php';

        if (! file_exists($viewPath)) {
            throw new ViewNotFoundException($name);
        }

        extract(array_merge($this->prepareData(), $data));

        require_once $viewPath;
    }

    public function component(string $name, array $data = []): void
    {
        $componentPath = APP_PATH.'/views/components/'.$name.'.php';

        if (! file_exists($componentPath)) {
            echo "Component $name does not exist";

            return;
        }

        extract(array_merge($this->prepareData(), $data));

        require $componentPath;
    }

    private function prepareData(): array
    {
        return [
            'view' => $this,
            'session' => $this->session,
            'auth' => $this->auth,
        ];
    }
}
