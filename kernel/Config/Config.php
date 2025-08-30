<?php

namespace Kernel\Config;

class Config implements ConfigInterface
{
    public static function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);

        if (! file_exists(APP_PATH.'/config/'.$keys[0].'.php')) {
            return $default;
        }

        $config = require APP_PATH.'/config/'.$keys[0].'.php';

        return $config[$keys[1]] ?? $default;
    }
}
