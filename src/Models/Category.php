<?php

namespace App\Models;

class Category
{
    public function __construct(
        private int $id,
        private string $title,
        private string $createdAt,
        private string $updatedAt,
    )
    {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }
}