<?php
declare(strict_types=1);

namespace App\Helpers;

class AuthHelper
{
    private const SESSION_ADMIN_ID = '_admin_id';
    private const SESSION_ADMIN_USER = '_admin_user';
    private const SESSION_LAST_ACTIVITY = '_last_activity';

    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_samesite', 'Lax');
            session_start();
        }

        // Check session timeout (2 hours)
        if (isset($_SESSION[self::SESSION_LAST_ACTIVITY])) {
            $inactiveTime = time() - $_SESSION[self::SESSION_LAST_ACTIVITY];
            if ($inactiveTime > 7200) {
                self::logout();
            }
        }
        $_SESSION[self::SESSION_LAST_ACTIVITY] = time();
    }

    public static function login(array $admin): void
    {
        self::initSession();
        session_regenerate_id(true);

        $_SESSION[self::SESSION_ADMIN_ID] = $admin['id'];
        $_SESSION[self::SESSION_ADMIN_USER] = [
            'id' => $admin['id'],
            'username' => $admin['username'],
            'email' => $admin['email'],
            'name' => $admin['name'],
            'role' => $admin['role'] ?? 'admin',
        ];
        $_SESSION[self::SESSION_LAST_ACTIVITY] = time();
    }

    public static function logout(): void
    {
        self::initSession();
        unset(
            $_SESSION[self::SESSION_ADMIN_ID],
            $_SESSION[self::SESSION_ADMIN_USER],
            $_SESSION[self::SESSION_LAST_ACTIVITY]
        );
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function check(): bool
    {
        self::initSession();
        return !empty($_SESSION[self::SESSION_ADMIN_ID]) && !empty($_SESSION[self::SESSION_ADMIN_USER]);
    }

    public static function user(): ?array
    {
        self::initSession();
        return $_SESSION[self::SESSION_ADMIN_USER] ?? null;
    }

    public static function id(): ?int
    {
        self::initSession();
        return isset($_SESSION[self::SESSION_ADMIN_ID]) ? (int) $_SESSION[self::SESSION_ADMIN_ID] : null;
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            FlashHelper::warning('Sesi Anda telah berakhir. Silakan login terlebih dahulu.');
            UrlHelper::redirect('/admin/login');
            exit;
        }
    }
}
