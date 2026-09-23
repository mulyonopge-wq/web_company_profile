<?php
declare(strict_types=1);

namespace Routes;

use App\Controllers\BaseController;
use Exception;

class Router
{
    private static array $routes = [];

    public static function get(string $path, string $handler): void
    {
        self::addRoute('GET', $path, $handler);
    }

    public static function post(string $path, string $handler): void
    {
        self::addRoute('POST', $path, $handler);
    }

    public static function any(string $path, string $handler): void
    {
        self::addRoute('GET', $path, $handler);
        self::addRoute('POST', $path, $handler);
    }

    private static function addRoute(string $method, string $path, string $handler): void
    {
        $path = '/' . trim($path, '/');
        // Convert route pattern {param} to regex named group (?P<param>[^/]+)
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $regex = '#^' . $pattern . '$#i';

        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'regex' => $regex,
            'handler' => $handler,
        ];
    }

    public static function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Normalize URL by removing project root prefix if running under subdirectory in Apache
        $config = require dirname(__DIR__) . '/config/config.php';
        $appUrlPath = parse_url($config['app']['url'], PHP_URL_PATH) ?? '';
        $appUrlPath = rtrim($appUrlPath, '/');

        if ($appUrlPath !== '' && str_starts_with($requestUri, $appUrlPath)) {
            $requestUri = substr($requestUri, strlen($appUrlPath));
        }

        // Also handle /public or /index.php if directly in URL
        if (str_starts_with($requestUri, '/index.php')) {
            $requestUri = substr($requestUri, strlen('/index.php'));
        }

        $requestUri = '/' . trim($requestUri, '/');

        foreach (self::$routes as $route) {
            if ($route['method'] !== $requestMethod && $route['method'] !== 'ANY') {
                continue;
            }

            if (preg_match($route['regex'], $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                self::executeHandler($route['handler'], $params);
                return;
            }
        }

        // No route matched: Render 404
        http_response_code(404);
        $errorController = new BaseController();
        $errorController->renderView('errors/404', ['title' => 'Halaman Tidak Ditemukan']);
    }

    private static function executeHandler(string $handler, array $params): void
    {
        [$controllerName, $action] = explode('@', $handler);

        $fullControllerClass = "App\\Controllers\\{$controllerName}";
        if (!class_exists($fullControllerClass)) {
            throw new Exception("Controller class '{$fullControllerClass}' not found.");
        }

        $controller = new $fullControllerClass();
        if (!method_exists($controller, $action)) {
            throw new Exception("Method '{$action}' not found in controller '{$fullControllerClass}'.");
        }

        call_user_func_array([$controller, $action], $params);
    }
}
