<?php
declare(strict_types=1);

namespace App\Helpers;

class UrlHelper
{
    private static ?string $baseUrl = null;

    public static function getBaseUrl(): string
    {
        if (self::$baseUrl === null) {
            $config = require dirname(__DIR__, 2) . '/config/config.php';
            self::$baseUrl = rtrim($config['app']['url'], '/');
        }
        return self::$baseUrl;
    }

    public static function base(string $path = ''): string
    {
        $base = self::getBaseUrl();
        $path = ltrim($path, '/');
        return $path === '' ? $base : "{$base}/{$path}";
    }

    public static function asset(string $path = ''): string
    {
        return self::base('assets/' . ltrim($path, '/'));
    }

    public static function upload(string $path = '', string $fallback = 'assets/images/no-image.svg'): string
    {
        if (empty($path)) {
            return self::base($fallback);
        }

        // If absolute URL is stored
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return self::base('uploads/' . ltrim($path, '/'));
    }

    public static function current(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return "{$protocol}://{$host}{$uri}";
    }

    public static function redirect(string $path): void
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            header("Location: {$path}");
        } else {
            $url = self::base($path);
            header("Location: {$url}");
        }
        exit;
    }

    public static function isActive(string $route, string $class = 'active'): string
    {
        $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $route = '/' . trim($route, '/');
        if ($route === '/') {
            return ($currentUri === '/' || $currentUri === '' || str_ends_with($currentUri, '/index.php')) ? $class : '';
        }
        return str_contains($currentUri, $route) ? $class : '';
    }

    public static function getYoutubeEmbedUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return null;
    }

    public static function getYoutubeThumbnailUrl(?string $url, string $quality = 'mqdefault'): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return 'https://img.youtube.com/vi/' . $matches[1] . '/' . $quality . '.jpg';
        }

        return null;
    }
}

