<?php

namespace Kernel\Http\Redirect;

class Redirect implements RedirectInterface
{
    public function to(string $uri): void
    {
        header("Location: $uri");
        exit;
    }
}
