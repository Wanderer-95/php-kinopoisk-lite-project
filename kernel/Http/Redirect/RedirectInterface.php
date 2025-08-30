<?php

namespace Kernel\Http\Redirect;

interface RedirectInterface
{
    public function to(string $uri): void;
}
