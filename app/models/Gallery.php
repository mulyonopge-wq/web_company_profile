<?php
declare(strict_types=1);

namespace App\Models;

class Gallery extends BaseModel
{
    public static function getActive(): array
    {
        return self::fetchAll("SELECT * FROM `galleries` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function all(): array
    {
        return self::fetchAll("SELECT * FROM `galleries` ORDER BY `sort_order` ASC, `id` DESC");
    }

    public static function findById(int $id): ?array
    {
        return self::fetch("SELECT * FROM `galleries` WHERE `id` = :id LIMIT 1", [':id' => $id]);
    }

    public static function create(array $data): int
    {
        return self::insert('galleries', $data);
    }

    public static function updateGallery(int $id, array $data): int
    {
        return self::update('galleries', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteGallery(int $id): int
    {
        return self::delete('galleries', '`id` = :id', [':id' => $id]);
    }

    public static function getCategories(): array
    {
        return self::fetchAll("SELECT DISTINCT `category` FROM `galleries` WHERE `category` != '' AND `category` IS NOT NULL ORDER BY `category` ASC");
    }
}
