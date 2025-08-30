<?php

namespace Kernel\Config;

interface ConfigInterface
{
    public static function get(string $key, mixed $default = null): mixed;
}
