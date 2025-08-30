<?php

namespace Kernel\Storage;

interface StorageInterface
{
    public static function url(string $path): string;

    public static function get(string $path): string;
}