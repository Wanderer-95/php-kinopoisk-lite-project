<?php

namespace Kernel;

use Kernel\Container\Container;

class App
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function run(): void
    {
        $this->container
            ->getRouter()
            ->dispatch($this->container->getRequest()->uri(), $this->container->getRequest()->method());
    }
}