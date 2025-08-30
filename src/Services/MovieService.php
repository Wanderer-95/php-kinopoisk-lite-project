<?php

namespace App\Services;

use Kernel\Database\DatabaseInterface;

class MovieService
{
    public static function all(DatabaseInterface $db): array
    {
        return $db->get('movies');
    }
}