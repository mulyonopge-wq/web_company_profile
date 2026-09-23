<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\AuthHelper;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Admin;

class UserController extends AdminBaseController
{
    public function index(): void
    {
        $users = Admin::all();
        $this->renderAdminView('admin/users/index', [
            'title' => 'Kelola Akun Administrator',
            'users' => $users,
        ]);
    }

    public function create(): void
    {
        $this->renderAdminView('admin/users/create', [
            'title' => 'Tambah Administrator Baru',
        ]);
    }

    public function store(): void
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'admin';

        if (empty($username) || empty($email) || empty($name) || empty($password)) {
            FlashHelper::error('Semua bidang wajib diisi.');
            UrlHelper::redirect('/admin/users/create');
            return;
        }

        if (Admin::findByUsernameOrEmail($username) || Admin::findByUsernameOrEmail($email)) {
            FlashHelper::error('Username atau Email sudah terdaftar.');
            UrlHelper::redirect('/admin/users/create');
            return;
        }

        Admin::create([
            'username' => $username,
            'email' => $email,
            'name' => $name,
            'password' => $password,
            'role' => $role,
        ]);

        FlashHelper::success("Pengguna \"{$username}\" berhasil ditambahkan.");
        UrlHelper::redirect('/admin/users');
    }

    public function edit(string|int $id): void
    {
        $user = Admin::findById((int) $id);
        if (!$user) {
            FlashHelper::error('Pengguna tidak ditemukan.');
            UrlHelper::redirect('/admin/users');
            return;
        }

        $this->renderAdminView('admin/users/edit', [
            'title' => 'Edit Pengguna: ' . $user['name'],
            'user' => $user,
        ]);
    }

    public function update(string|int $id): void
    {
        $user = Admin::findById((int) $id);
        if (!$user) {
            FlashHelper::error('Pengguna tidak ditemukan.');
            UrlHelper::redirect('/admin/users');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'admin';
        $password = trim($_POST['password'] ?? '');

        $updateData = [
            'name' => $name,
            'email' => $email,
            'role' => $role,
        ];

        if (!empty($password)) {
            $updateData['password'] = $password;
        }

        Admin::updateAdmin((int) $id, $updateData);
        FlashHelper::success('Data pengguna berhasil diperbarui.');
        UrlHelper::redirect('/admin/users');
    }

    public function delete(string|int $id): void
    {
        $userId = (int) $id;
        if ($userId === AuthHelper::id()) {
            FlashHelper::error('Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
            UrlHelper::redirect('/admin/users');
            return;
        }

        Admin::deleteAdmin($userId);
        FlashHelper::success('Pengguna berhasil dihapus.');
        UrlHelper::redirect('/admin/users');
    }
}
