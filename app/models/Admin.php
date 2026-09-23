<?php
declare(strict_types=1);

namespace App\Models;

class Admin extends BaseModel
{
    public static function findByUsernameOrEmail(string $login): ?array
    {
        return self::fetch(
            'SELECT * FROM `admins` WHERE `username` = :username OR `email` = :email LIMIT 1',
            [':username' => $login, ':email' => $login]
        );
    }

    public static function findById(int $id): ?array
    {
        return self::fetch('SELECT * FROM `admins` WHERE `id` = :id LIMIT 1', [':id' => $id]);
    }

    public static function all(): array
    {
        return self::fetchAll('SELECT `id`, `username`, `email`, `name`, `role`, `created_at` FROM `admins` ORDER BY `id` ASC');
    }

    public static function create(array $data): int
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return self::insert('admins', $data);
    }

    public static function updateAdmin(int $id, array $data): int
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return self::update('admins', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteAdmin(int $id): int
    {
        return self::delete('admins', '`id` = :id', [':id' => $id]);
    }
}
