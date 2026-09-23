<?php
declare(strict_types=1);

namespace App\Models;

class Page extends BaseModel
{
    public static function findBySlug(string $slug): ?array
    {
        return self::fetch("SELECT * FROM `pages` WHERE `slug` = :slug LIMIT 1", [':slug' => $slug]);
    }

    public static function all(): array
    {
        return self::fetchAll("SELECT * FROM `pages` ORDER BY `id` ASC");
    }

    public static function updateBySlug(string $slug, array $data): int
    {
        return self::update('pages', $data, '`slug` = :slug', [':slug' => $slug]);
    }
}
