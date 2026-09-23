<?php
declare(strict_types=1);

namespace App\Models;

class Customer extends BaseModel
{
    public static function findOrCreate(string $name, string $phone, ?string $email, string $address): int
    {
        $existing = self::fetch('SELECT id, total_orders FROM `customers` WHERE `phone` = :phone LIMIT 1', [':phone' => $phone]);

        if ($existing) {
            self::update('customers', [
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'total_orders' => ((int) $existing['total_orders']) + 1,
            ], '`id` = :id', [':id' => $existing['id']]);

            return (int) $existing['id'];
        }

        return self::insert('customers', [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'total_orders' => 1,
        ]);
    }

    public static function all(): array
    {
        return self::fetchAll('SELECT * FROM `customers` ORDER BY `total_orders` DESC, `id` DESC');
    }

    public static function findById(int $id): ?array
    {
        return self::fetch('SELECT * FROM `customers` WHERE `id` = :id LIMIT 1', [':id' => $id]);
    }
}
