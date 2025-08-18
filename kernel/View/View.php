<?php

namespace Kernel\View;

use kernel\Exceptions\ViewNotFoundException;
use Kernel\Session\Session;

class View
{
    public function __construct(private Session $session)
    {
    }

    public function render(string $name): void
    {
        $viewPath = APP_PATH.'/views/pages/'.$name.'.php';

        if (! file_exists($viewPath)) {
            throw new ViewNotFoundException($name);
        }

        extract($this->prepareData());

        require_once $viewPath;
    }

    public function component(string $name): void
    {
        $componentPath = APP_PATH.'/views/components/'.$name.'.php';

        if (! file_exists($componentPath)) {
            echo "Component $name does not exist";
            return;
        }

        extract($this->prepareData());

        require_once $componentPath;
    }

    private function prepareData(): array
    {
        return [
            'view' => $this,
            'session' => $this->session
        ];
    }
}
