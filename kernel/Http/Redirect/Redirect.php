<?php

namespace Kernel\Http\Redirect;

class Redirect
{
    public function to(string $uri): void
    {
        header("Location: $uri");
        exit;
    }
}