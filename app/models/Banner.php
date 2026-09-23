<?php
declare(strict_types=1);

namespace App\Models;

class Banner extends BaseModel
{
    public static function getActive(): array
    {
        return self::fetchAll('SELECT * FROM `banners` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` DESC');
    }

    public static function all(): array
    {
        return self::fetchAll('SELECT * FROM `banners` ORDER BY `sort_order` ASC, `id` DESC');
    }

    public static function findById(int $id): ?array
    {
        return self::fetch('SELECT * FROM `banners` WHERE `id` = :id LIMIT 1', [':id' => $id]);
    }

    public static function create(array $data): int
    {
        return self::insert('banners', $data);
    }

    public static function updateBanner(int $id, array $data): int
    {
        return self::update('banners', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteBanner(int $id): int
    {
        return self::delete('banners', '`id` = :id', [':id' => $id]);
    }
}
