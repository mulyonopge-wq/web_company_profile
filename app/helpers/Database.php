<?php
declare(strict_types=1);

namespace App\Helpers;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';
            $dbConfig = $config['connections'][$config['default']];

            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s;charset=%s',
                $dbConfig['driver'],
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['database'],
                $dbConfig['charset']
            );

            try {
                self::$instance = new PDO(
                    $dsn,
                    $dbConfig['username'],
                    $dbConfig['password'],
                    $dbConfig['options']
                );
            } catch (PDOException $e) {
                // Log the real error in storage/logs
                self::logError($e->getMessage());
                throw new RuntimeException('Koneksi database gagal. Silakan periksa konfigurasi .env atau database.sql.');
            }
        }

        return self::$instance;
    }

    public static function logError(string $message): void
    {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        $date = date('Y-m-d H:i:s');
        $logFile = $logDir . '/app.log';
        @file_put_contents($logFile, "[{$date}] [DB ERROR] {$message}" . PHP_EOL, FILE_APPEND);
    }
}
