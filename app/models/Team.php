<?php
declare(strict_types=1);

namespace App\Models;

class Team extends BaseModel
{
    private static bool $tableChecked = false;

    public static function ensureTableExists(): void
    {
        if (self::$tableChecked) {
            return;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `teams` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `position` VARCHAR(150) NOT NULL,
            `photo` VARCHAR(255) NULL,
            `bio` TEXT NULL,
            `sort_order` INT NOT NULL DEFAULT 0,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_team_order` (`sort_order`),
            INDEX `idx_team_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        self::query($sql);
        self::$tableChecked = true;

        // Cek jika kosong, beri data awal representatif
        $count = (int) self::fetchColumn("SELECT COUNT(*) FROM `teams`");
        if ($count === 0) {
            $defaults = [
                [
                    'name' => 'Dr. H. Muhammad Arifin, M.M.',
                    'position' => 'Komisaris Utama',
                    'photo' => '',
                    'bio' => 'Berpengalaman lebih dari 20 tahun dalam tata kelola korporasi, kepemimpinan strategis, dan investasi teknologi.',
                    'sort_order' => 1,
                    'is_active' => 1
                ],
                [
                    'name' => 'Ir. Hendra Gunawan, S.T., M.T.',
                    'position' => 'Direktur Utama',
                    'photo' => '',
                    'bio' => 'Memimpin arah strategis perusahaan, kemitraan global, dan inovasi integrasi infrastruktur digital di Indonesia.',
                    'sort_order' => 2,
                    'is_active' => 1
                ],
                [
                    'name' => 'Bambang Pratama, S.Kom., CCNA',
                    'position' => 'Direktur Operasional & Teknologi',
                    'photo' => '',
                    'bio' => 'Mengawasi operasional logistik, implementasi solusi jaringan enterprise, dan standardisasi kualitas layanan purna jual.',
                    'sort_order' => 3,
                    'is_active' => 1
                ],
                [
                    'name' => 'Siti Rahmawati, S.E., Ak.',
                    'position' => 'Manajer Keuangan & Administrasi',
                    'photo' => '',
                    'bio' => 'Bertanggung jawab atas efisiensi finansial, kepatuhan akuntansi korporat, dan manajemen hubungan kemitraan perbankan.',
                    'sort_order' => 4,
                    'is_active' => 1
                ],
            ];

            foreach ($defaults as $d) {
                self::insert('teams', $d);
            }
        }
    }

    public static function getActive(): array
    {
        self::ensureTableExists();
        return self::fetchAll('SELECT * FROM `teams` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC');
    }

    public static function all(): array
    {
        self::ensureTableExists();
        return self::fetchAll('SELECT * FROM `teams` ORDER BY `sort_order` ASC, `id` ASC');
    }

    public static function findById(int $id): ?array
    {
        self::ensureTableExists();
        return self::fetch('SELECT * FROM `teams` WHERE `id` = :id LIMIT 1', [':id' => $id]);
    }

    public static function create(array $data): int
    {
        self::ensureTableExists();
        return self::insert('teams', $data);
    }

    public static function updateTeam(int $id, array $data): int
    {
        self::ensureTableExists();
        return self::update('teams', $data, '`id` = :id', [':id' => $id]);
    }

    public static function deleteTeam(int $id): int
    {
        self::ensureTableExists();
        return self::delete('teams', '`id` = :id', [':id' => $id]);
    }
}
