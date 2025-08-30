<?php

namespace Kernel\Middleware;

use Kernel\Auth\AuthInterface;
use Kernel\Http\Redirect\RedirectInterface;
use Kernel\Http\Request\RequestInterface;

abstract class AbstractMiddleware
{
    public function __construct(
        protected RequestInterface $request,
        protected AuthInterface $auth,
        protected RedirectInterface $redirect
    ) {}

    abstract public function handle(): void;
}
