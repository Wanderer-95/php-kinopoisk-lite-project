<?php

namespace Kernel\View;

interface ViewInterface
{
    public function render(string $name, array $data = []): void;

    public function component(string $name, array $data = []): void;
}
