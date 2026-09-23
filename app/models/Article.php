<?php
declare(strict_types=1);

namespace App\Models;

class Article extends BaseModel
{
    public static function getPublished(?int $limit = null): array
    {
        $sql = "SELECT * FROM `articles` WHERE `status` = 'published' ORDER BY `published_at` DESC, `id` DESC";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }
        return self::fetchAll($sql);
    }

    public static function findBySlug(string $slug): ?array
    {
        return self::fetch("SELECT * FROM `articles` WHERE `slug` = :slug LIMIT 1", [':slug' => $slug]);
    }

    public static function findById(int $id): ?array
    {
        return self::fetch("SELECT * FROM `articles` WHERE `id` = :id LIMIT 1", [':id' => $id]);
    }

    public static function all(): array
    {
        return self::fetchAll("SELECT * FROM `articles` ORDER BY `id` DESC");
    }

    public static function create(array $data): int
    {
        if (!empty($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        return self::insert('articles', $data);
    }

    public static function updateArticle(int $id, array $data): int
    {
        if (!empty($data['status']) && $data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        return self::update('articles', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteArticle(int $id): int
    {
        return self::delete('articles', '`id` = :id', [':id' => $id]);
    }
}
