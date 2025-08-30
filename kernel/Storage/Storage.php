<?php

namespace Kernel\Storage;

use Kernel\Config\Config;

class Storage implements StorageInterface
{
    public static function url(string $path): string
    {
        $url = Config::get('app.url');
        return $url.'/storage/'.$path;
    }

    public static function get(string $path): string
    {
        return file_get_contents(APP_PATH.'/storage/'.$path);
    }
}