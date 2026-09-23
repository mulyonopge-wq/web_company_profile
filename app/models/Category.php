<?php
declare(strict_types=1);

namespace App\Models;

class Category extends BaseModel
{
    public static function getActiveWithCount(): array
    {
        $sql = 'SELECT c.*, COUNT(p.id) as product_count
                FROM `categories` c
                LEFT JOIN `products` p ON p.category_id = c.id AND p.is_active = 1
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC';
        return self::fetchAll($sql);
    }

    public static function all(): array
    {
        $sql = 'SELECT c.*, COUNT(p.id) as product_count
                FROM `categories` c
                LEFT JOIN `products` p ON p.category_id = c.id
                GROUP BY c.id
                ORDER BY c.sort_order ASC, c.name ASC';
        return self::fetchAll($sql);
    }

    public static function findById(int $id): ?array
    {
        return self::fetch('SELECT * FROM `categories` WHERE `id` = :id LIMIT 1', [':id' => $id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::fetch('SELECT * FROM `categories` WHERE `slug` = :slug LIMIT 1', [':slug' => $slug]);
    }

    public static function create(array $data): int
    {
        return self::insert('categories', $data);
    }

    public static function updateCategory(int $id, array $data): int
    {
        return self::update('categories', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteCategory(int $id): int
    {
        return self::delete('categories', '`id` = :id', [':id' => $id]);
    }
}
