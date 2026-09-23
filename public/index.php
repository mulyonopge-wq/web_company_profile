<?php
declare(strict_types=1);

/**
 * Company Profile & Mini Marketplace
 * Front Controller
 */

// CLI-server static file bypass
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $staticFile = __DIR__ . $path;
    if ($path !== '/' && is_file($staticFile)) {
        return false;
    }
}

// 1. Register PSR-4 Autoloader
spl_autoload_register(function (string $class) {
    $prefixes = [
        'App\\' => dirname(__DIR__) . '/app/',
        'Routes\\' => dirname(__DIR__) . '/routes/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }

        $relativeClass = substr($class, $len);

        // 1. Try exact path match
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }

        // 2. Try lowercase directory paths for Linux / case-sensitive OS (e.g. Helpers/Env -> helpers/Env)
        $parts = explode('\\', $relativeClass);
        $className = array_pop($parts);
        $subDir = !empty($parts) ? strtolower(implode('/', $parts)) . '/' : '';
        $fileLowerDir = $baseDir . $subDir . $className . '.php';
        if (file_exists($fileLowerDir)) {
            require $fileLowerDir;
            return;
        }
    }
});

// 2. Load Helpers & Functions
require_once dirname(__DIR__) . '/app/helpers/Env.php';
require_once dirname(__DIR__) . '/app/helpers/Sanitizer.php';
require_once dirname(__DIR__) . '/app/helpers/UrlHelper.php';
require_once dirname(__DIR__) . '/app/helpers/functions.php';

// 3. Load Configurations
$config = require dirname(__DIR__) . '/config/config.php';

// Set Timezone
date_default_timezone_set($config['app']['timezone'] ?? 'Asia/Jakarta');

// 4. Error & Exception Handling
if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Uncaught Exception Handler
set_exception_handler(function (Throwable $e) use ($config) {
    $logDir = dirname(__DIR__) . '/storage/logs';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $date = date('Y-m-d H:i:s');
    $logMsg = "[{$date}] " . get_class($e) . ": " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
    @file_put_contents($logDir . '/app.log', $logMsg, FILE_APPEND);

    http_response_code(500);

    if ($config['app']['debug']) {
        echo "<div style='font-family:sans-serif;padding:30px;background:#fff5f5;border-left:5px solid #dc3545;margin:20px;'>";
        echo "<h2 style='color:#dc3545;'>Application Exception</h2>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') . ":" . $e->getLine() . "</p>";
        echo "<pre style='background:#f8f9fa;padding:15px;overflow:auto;'>" . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') . "</pre>";
        echo "</div>";
    } else {
        $viewFile = dirname(__DIR__) . '/app/views/errors/500.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "<h1>500 - Terjadi Kesalahan Server</h1><p>Mohon maaf, sistem sedang mengalami kendala. Silakan coba beberapa saat lagi.</p>";
        }
    }
    exit;
});

// 5. Initialize Secure Session
\App\Helpers\AuthHelper::initSession();

// 6. Load Routes
require_once dirname(__DIR__) . '/routes/web.php';

// 7. Dispatch Request
\Routes\Router::dispatch();
