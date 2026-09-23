<?php
declare(strict_types=1);

namespace App\Models;

class Product extends BaseModel
{
    public static function getFeatured(int $limit = 8): array
    {
        $sql = 'SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE p.is_active = 1 AND p.is_featured = 1
                ORDER BY p.id DESC
                LIMIT :limit';
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getLatest(int $limit = 8): array
    {
        $sql = 'SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE p.is_active = 1
                ORDER BY p.id DESC
                LIMIT :limit';
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getRelated(int $categoryId, int $currentProductId, int $limit = 4): array
    {
        $sql = 'SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE p.is_active = 1 AND p.category_id = :catId AND p.id != :currId
                ORDER BY p.id DESC
                LIMIT :limit';
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':catId', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue(':currId', $currentProductId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $sql = 'SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE p.slug = :slug LIMIT 1';
        return self::fetch($sql, [':slug' => $slug]);
    }

    public static function findById(int $id): ?array
    {
        $sql = 'SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE p.id = :id LIMIT 1';
        return self::fetch($sql, [':id' => $id]);
    }

    public static function filter(array $filters = [], int $page = 1, int $perPage = 12): array
    {
        $where = ['p.is_active = 1'];
        $params = [];

        // Search by query (name, sku, description)
        if (!empty($filters['q'])) {
            $where[] = '(p.name LIKE :query1 OR p.sku LIKE :query2 OR p.short_description LIKE :query3)';
            $qVal = '%' . trim($filters['q']) . '%';
            $params[':query1'] = $qVal;
            $params[':query2'] = $qVal;
            $params[':query3'] = $qVal;
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $where[] = 'p.category_id = :category_id';
            $params[':category_id'] = (int) $filters['category_id'];
        }

        if (!empty($filters['category_slug'])) {
            $where[] = 'c.slug = :category_slug';
            $params[':category_slug'] = trim($filters['category_slug']);
        }

        // Price range
        if (!empty($filters['min_price'])) {
            $where[] = '(CASE WHEN p.discount_price > 0 THEN p.discount_price ELSE p.price END) >= :min_price';
            $params[':min_price'] = (float) $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $where[] = '(CASE WHEN p.discount_price > 0 THEN p.discount_price ELSE p.price END) <= :max_price';
            $params[':max_price'] = (float) $filters['max_price'];
        }

        $whereSql = implode(' AND ', $where);

        // Count total
        $countSql = "SELECT COUNT(*) FROM `products` p LEFT JOIN `categories` c ON c.id = p.category_id WHERE {$whereSql}";
        $total = (int) self::fetchColumn($countSql, $params);

        // Sorting
        $sort = $filters['sort'] ?? 'newest';
        $orderBy = match ($sort) {
            'price_asc' => '(CASE WHEN p.discount_price > 0 THEN p.discount_price ELSE p.price END) ASC',
            'price_desc' => '(CASE WHEN p.discount_price > 0 THEN p.discount_price ELSE p.price END) DESC',
            'name_asc' => 'p.name ASC',
            'name_desc' => 'p.name DESC',
            default => 'p.id DESC',
        };

        // Pagination
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE {$whereSql}
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = self::db()->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll();

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }

    public static function searchAutocomplete(string $term, int $limit = 6): array
    {
        $term = '%' . trim($term) . '%';
        $sql = 'SELECT p.id, p.name, p.slug, p.sku, p.price, p.discount_price, p.main_image, c.name as category_name
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                WHERE p.is_active = 1 AND (p.name LIKE :term1 OR p.sku LIKE :term2 OR c.name LIKE :term3)
                ORDER BY p.is_featured DESC, p.id DESC
                LIMIT :limit';
        $stmt = self::db()->prepare($sql);
        $stmt->bindValue(':term1', $term);
        $stmt->bindValue(':term2', $term);
        $stmt->bindValue(':term3', $term);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getGalleryImages(int $productId): array
    {
        return self::fetchAll('SELECT * FROM `product_images` WHERE `product_id` = :id ORDER BY `sort_order` ASC, `id` ASC', [':id' => $productId]);
    }

    public static function addGalleryImage(int $productId, string $imagePath, int $sortOrder = 0): int
    {
        return self::insert('product_images', [
            'product_id' => $productId,
            'image_path' => $imagePath,
            'sort_order' => $sortOrder,
        ]);
    }

    public static function deleteGalleryImage(int $imageId): int
    {
        return self::delete('product_images', '`id` = :id', [':id' => $imageId]);
    }

    public static function createProduct(array $data): int
    {
        return self::insert('products', $data);
    }

    public static function updateProduct(int $id, array $data): int
    {
        return self::update('products', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteProduct(int $id): int
    {
        // First delete gallery images
        self::delete('product_images', '`product_id` = :id', [':id' => $id]);
        return self::delete('products', '`id` = :id', [':id' => $id]);
    }
}
