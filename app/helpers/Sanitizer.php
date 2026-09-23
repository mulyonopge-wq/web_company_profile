<?php
declare(strict_types=1);

namespace App\Helpers;

class Sanitizer
{
    /**
     * Escape HTML output (XSS prevention)
     */
    public static function e(?string $string): string
    {
        return htmlspecialchars((string) $string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Clean string input
     */
    public static function clean(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::clean($value);
            }
            return $data;
        }

        if (is_string($data)) {
            return trim($data);
        }

        return $data;
    }

    /**
     * Convert title to SEO friendly URL slug
     */
    public static function slugify(string $text): string
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text) ?: $text;
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        return empty($text) ? 'n-a-' . time() : $text;
    }

    /**
     * Format number to Indonesian Rupiah currency
     */
    public static function formatRupiah(float|int|string|null $number, bool $withPrefix = true): string
    {
        $num = (float) ($number ?? 0);
        $formatted = number_format($num, 0, ',', '.');
        return $withPrefix ? 'Rp ' . $formatted : $formatted;
    }

    /**
     * Truncate text cleanly with ellipsis
     */
    public static function truncate(string $text, int $limit = 120, string $end = '...'): string
    {
        $clean = strip_tags($text);
        if (mb_strlen($clean) <= $limit) {
            return $clean;
        }
        return mb_substr($clean, 0, $limit) . $end;
    }
}

