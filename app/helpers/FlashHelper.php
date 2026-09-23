<?php
declare(strict_types=1);

namespace App\Helpers;

class FlashHelper
{
    private const SESSION_KEY = '_flash_messages';

    public static function set(string $type, string $message): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }

        $_SESSION[self::SESSION_KEY][] = [
            'type' => $type, // success, danger, warning, info
            'message' => $message,
        ];
    }

    public static function success(string $message): void
    {
        self::set('success', $message);
    }

    public static function error(string $message): void
    {
        self::set('danger', $message);
    }

    public static function warning(string $message): void
    {
        self::set('warning', $message);
    }

    public static function info(string $message): void
    {
        self::set('info', $message);
    }

    public static function get(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $messages = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);

        return $messages;
    }

    public static function render(): string
    {
        $messages = self::get();
        if (empty($messages)) {
            return '';
        }

        $html = '<div class="flash-messages-container my-3">';
        foreach ($messages as $msg) {
            $type = htmlspecialchars($msg['type'], ENT_QUOTES, 'UTF-8');
            $text = htmlspecialchars($msg['message'], ENT_QUOTES, 'UTF-8');
            $html .= "
            <div class=\"alert alert-{$type} alert-dismissible fade show shadow-sm\" role=\"alert\">
                <div class=\"d-flex align-items-center\">
                    <i class=\"bi " . ($type === 'success' ? 'bi-check-circle-fill' : ($type === 'danger' ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill')) . " me-2 fs-5\"></i>
                    <div>{$text}</div>
                </div>
                <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
            </div>";
        }
        $html .= '</div>';

        return $html;
    }
}
