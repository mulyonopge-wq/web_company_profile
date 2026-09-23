<?php
declare(strict_types=1);

namespace App\Models;

class Setting extends BaseModel
{
    private static ?array $cachedSettings = null;

    public static function getAllSettings(bool $forceFresh = false): array
    {
        if (!$forceFresh && self::$cachedSettings !== null) {
            return self::$cachedSettings;
        }

        try {
            $rows = self::fetchAll('SELECT `setting_key`, `setting_value` FROM `settings`');
            $settings = [];
            foreach ($rows as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            self::$cachedSettings = $settings;
            return $settings;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = self::getAllSettings();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, ?string $value, string $group = 'general'): void
    {
        $existing = self::fetch('SELECT `id` FROM `settings` WHERE `setting_key` = :key', [':key' => $key]);
        if ($existing) {
            self::update('settings', ['setting_value' => $value], '`setting_key` = :k', [':k' => $key]);
        } else {
            self::insert('settings', [
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_group' => $group,
            ]);
        }
        self::$cachedSettings = null; // Invalidate cache
    }

    public static function updateMultiple(array $data, string $group = 'general'): void
    {
        foreach ($data as $key => $value) {
            // Ignore system or csrf tokens
            if (str_starts_with($key, '_')) {
                continue;
            }
            self::set($key, is_array($value) ? json_encode($value) : (string) $value, $group);
        }
        self::$cachedSettings = null;
    }

    public static function getByGroup(string $group): array
    {
        $rows = self::fetchAll('SELECT `setting_key`, `setting_value` FROM `settings` WHERE `setting_group` = :grp', [':grp' => $group]);
        $result = [];
        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }
}
