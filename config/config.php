<?php
declare(strict_types=1);

use App\Helpers\Env;

// Load environment variables
Env::load(dirname(__DIR__) . '/.env');

return [
    'app' => [
        'name' => Env::get('APP_NAME', 'Solusi Tekno Nusantara'),
        'version' => '1.0.0',
        'env' => Env::get('APP_ENV', 'production'),
        'debug' => (bool) Env::get('APP_DEBUG', false),
        'url' => rtrim((string) Env::get('APP_URL', 'http://localhost/web-company-profile/public'), '/'),
        'timezone' => 'Asia/Jakarta',
        'locale' => 'id_ID',
        'currency' => 'Rp',
    ],
    'paths' => [
        'root' => dirname(__DIR__),
        'app' => dirname(__DIR__) . '/app',
        'public' => dirname(__DIR__) . '/public',
        'uploads' => dirname(__DIR__) . '/public/uploads',
        'storage' => dirname(__DIR__) . '/storage',
        'logs' => dirname(__DIR__) . '/storage/logs',
        'views' => dirname(__DIR__) . '/app/views',
    ],
    'upload' => [
        'max_size_mb' => (int) Env::get('MAX_UPLOAD_SIZE_MB', 5),
        'allowed_extensions' => explode(',', (string) Env::get('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,webp')),
        'allowed_mimes' => [
            'image/jpeg',
            'image/png',
            'image/webp',
        ],
    ],
    'session' => [
        'lifetime' => 7200, // 2 hours
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ],
];
