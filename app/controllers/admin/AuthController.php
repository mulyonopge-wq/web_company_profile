<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\AuthHelper;
use App\Helpers\CsrfHelper;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Admin;

class AuthController extends BaseController
{
    public function login(): void
    {
        if (AuthHelper::check()) {
            UrlHelper::redirect('/admin/dashboard');
            return;
        }

        $this->renderView('admin/login', [
            'title' => 'Login Administrator',
        ], null); // Render without standard layout
    }

    public function processLogin(): void
    {
        CsrfHelper::verifyPost();

        $login = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($login) || empty($password)) {
            FlashHelper::error('Username / Email dan Password wajib diisi.');
            UrlHelper::redirect('/admin/login');
            return;
        }

        $admin = Admin::findByUsernameOrEmail($login);

        if (!$admin || !password_verify($password, $admin['password'])) {
            // Delay to prevent brute-force timing attacks
            usleep(250000);
            FlashHelper::error('Username atau Password yang Anda masukkan salah.');
            UrlHelper::redirect('/admin/login');
            return;
        }

        AuthHelper::login($admin);
        FlashHelper::success("Selamat datang kembali, {$admin['name']}!");
        UrlHelper::redirect('/admin/dashboard');
    }

    public function logout(): void
    {
        AuthHelper::logout();
        FlashHelper::info('Anda telah berhasil keluar (logout).');
        UrlHelper::redirect('/admin/login');
    }
}
