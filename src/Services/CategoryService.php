<?php

namespace App\Services;

use Kernel\Database\DatabaseInterface;
use App\Models\Category;

class CategoryService
{
    public static function all(DatabaseInterface $db): array
    {
        $categories = $db->get('categories');
        return array_map(function ($category) {
            return new Category(
                $category['id'],
                $category['title'],
                $category['created_at'],
                $category['updated_at'],
            );
        }, $categories);
    }

    public static function first(DatabaseInterface $db, array $conditions): Category
    {
        $category = $db->first('categories', $conditions);
        return new Category(
            $category['id'],
            $category['title'],
            $category['created_at'],
            $category['updated_at'],
        );
    }

    public static function store(DatabaseInterface $db, array $data): int|false
    {
        return $db->insert('categories', $data);
    }

    public static function update(DatabaseInterface $db, array $data, array $conditions = []): ?Category
    {
        $category = $db->update('categories', $data, $conditions);

        if (! $category) {
            return null;
        }
        return new Category(
            $category['id'],
            $category['title'],
            $category['created_at'],
            $category['updated_at'],
        );
    }
}