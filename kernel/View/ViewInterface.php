<?php

namespace Kernel\View;

interface ViewInterface
{
    public function render(string $name): void;
    public function component(string $name): void;
}