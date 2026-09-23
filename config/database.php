<?php
declare(strict_types=1);

use App\Helpers\Env;

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => Env::get('DB_HOST', '127.0.0.1'),
            'port'      => (int) Env::get('DB_PORT', 3306),
            'database'  => Env::get('DB_NAME', 'company_marketplace'),
            'username'  => Env::get('DB_USER', 'root'),
            'password'  => Env::get('DB_PASS', ''),
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'options'   => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ],
    ],
];
