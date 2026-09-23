<?php
declare(strict_types=1);

/**
 * Global Helper Functions
 */

use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;

if (!function_exists('e')) {
    function e(?string $string): string
    {
        return Sanitizer::e($string);
    }
}

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        return UrlHelper::base($path);
    }
}

if (!function_exists('asset_url')) {
    function asset_url(string $path = ''): string
    {
        return UrlHelper::asset($path);
    }
}

if (!function_exists('upload_url')) {
    function upload_url(string $path = '', string $fallback = 'assets/images/no-image.svg'): string
    {
        return UrlHelper::upload($path, $fallback);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        UrlHelper::redirect($path);
    }
}
