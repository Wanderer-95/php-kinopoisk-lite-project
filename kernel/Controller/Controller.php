<?php

namespace Kernel\Controller;

use Kernel\View\View;

abstract class Controller
{
    private View $view;

    public function view(string $name): void
    {
        $this->view->render($name);
    }

    public function setView(View $view): void
    {
        $this->view = $view;
    }
}