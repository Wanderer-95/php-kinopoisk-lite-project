<?php

namespace Kernel\View;

class View
{
    public function render(string $name): void
    {
        require_once APP_PATH . '/views/pages/' . $name . '.php';
    }
}