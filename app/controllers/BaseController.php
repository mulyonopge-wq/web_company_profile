<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Models\Setting;
use Exception;

class BaseController
{
    protected array $settings = [];

    public function __construct()
    {
        // Safe load settings
        try {
            $this->settings = Setting::getAllSettings();
        } catch (Exception $e) {
            $this->settings = [];
        }
    }

    /**
     * Render a view file with layout wrapping
     */
    public function renderView(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void
    {
        // Extract variables for view
        $data['settings'] = $this->settings;
        $data['cart_count'] = $this->getCartCount();
        extract($data);

        $viewsDir = dirname(__DIR__) . '/views/';
        $viewFile = $viewsDir . trim($viewPath, '/') . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View file not found: {$viewFile}");
        }

        // Buffer view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If layout specified, wrap in layout
        if ($layout !== null) {
            $layoutFile = $viewsDir . trim($layout, '/') . '.php';
            if (file_exists($layoutFile)) {
                require $layoutFile;
                return;
            }
        }

        echo $content;
    }

    /**
     * JSON Response for AJAX endpoints
     */
    public function jsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Get total quantity of items in session cart
     */
    protected function getCartCount(): int
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            @session_start();
        }

        $cart = $_SESSION['cart'] ?? [];
        $totalQty = 0;
        foreach ($cart as $item) {
            $totalQty += (int) ($item['quantity'] ?? 0);
        }

        return $totalQty;
    }
}
