<?php

namespace App\Models;

class Movie
{
    public function __construct(
        private int $id,
        private string $title,
        private string $createdAt,
        private string $updatedAt,
    )
    {
        dd($id,
$title,
$createdAt,
$updatedAt);
    }
}