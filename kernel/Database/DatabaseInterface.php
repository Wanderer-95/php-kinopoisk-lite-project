<?php

namespace Kernel\Database;

interface DatabaseInterface
{
    public function connect(): void;
}