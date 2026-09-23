<?php
declare(strict_types=1);

/**
 * Interactive / CLI Database Installer
 * Usage: php database/install.php
 */

require_once dirname(__DIR__) . '/app/helpers/Env.php';
\App\Helpers\Env::load(dirname(__DIR__) . '/.env');

$host = \App\Helpers\Env::get('DB_HOST', '127.0.0.1');
$port = (int) \App\Helpers\Env::get('DB_PORT', 3306);
$dbName = \App\Helpers\Env::get('DB_NAME', 'company_marketplace');
$user = \App\Helpers\Env::get('DB_USER', 'root');
$pass = \App\Helpers\Env::get('DB_PASS', '');

echo "===================================================\n";
echo " Installer Database: {$dbName}\n";
echo " Host: {$host}:{$port} | User: {$user}\n";
echo "===================================================\n\n";

try {
    // 1. Connect without DB name first to create database if not exists
    $dsn = "mysql:host={$host};port={$port};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "[1/3] Membuat database '{$dbName}' jika belum ada...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "      -> Database '{$dbName}' siap.\n\n";

    // 2. Connect to the specific database
    $pdo->exec("USE `{$dbName}`;");

    // Safety guard: Check if tables already exist
    $tables = $pdo->query("SHOW TABLES LIKE 'settings'")->fetchAll();
    $force = in_array('--force', $argv ?? []);
    if (!empty($tables) && !$force) {
        echo "[INFO] Database '{$dbName}' sudah berisi data/tabel.\n";
        echo "Installer dibatalkan untuk mencegah data tertimpa atau terhapus.\n";
        echo "Jalankan 'php database/install.php --force' jika ingin mereset ulang secara paksa.\n";
        exit(0);
    }

    // 3. Read and execute database.sql
    echo "[2/3] Membaca file database/database.sql...\n";
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("File database.sql tidak ditemukan di: {$sqlFile}");
    }

    $sql = file_get_contents($sqlFile);
    echo "[3/3] Mengimpor tabel dan data awal (seed)...\n";
    $pdo->exec($sql);

    echo "\n===================================================\n";
    echo " SUKSES: Database berhasil di-setup dan diisi!\n";
    echo " Akun Admin Default:\n";
    echo " Username: admin\n";
    echo " Password: Admin12345!\n";
    echo "===================================================\n";
} catch (Throwable $e) {
    echo "\n[GAGAL] " . $e->getMessage() . "\n";
    echo "Pastikan layanan MySQL di XAMPP / server Anda sudah aktif.\n";
    exit(1);
}
