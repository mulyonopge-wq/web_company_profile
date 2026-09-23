<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Models\Category;

class CategoryController extends AdminBaseController
{
    public function index(): void
    {
        $categories = Category::all();
        $this->renderAdminView('admin/categories/index', [
            'title' => 'Kelola Kategori Produk',
            'categories' => $categories,
        ]);
    }

    public function create(): void
    {
        $this->renderAdminView('admin/categories/create', [
            'title' => 'Tambah Kategori Produk',
        ]);
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name)) {
            FlashHelper::error('Nama kategori wajib diisi.');
            UrlHelper::redirect('/admin/categories/create');
            return;
        }

        if (empty($slug)) {
            $slug = Sanitizer::slugify($name);
        } else {
            $slug = Sanitizer::slugify($slug);
        }

        // Check unique slug
        $existing = Category::findBySlug($slug);
        if ($existing) {
            $slug .= '-' . time();
        }

        $iconPath = '';
        if (!empty($_FILES['icon_image']['name'])) {
            $upload = FileUpload::upload($_FILES['icon_image'], 'categories');
            if ($upload['success']) {
                $iconPath = $upload['path'];
            }
        }

        Category::create([
            'name' => $name,
            'slug' => $slug,
            'icon_image' => $iconPath,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        FlashHelper::success("Kategori \"{$name}\" berhasil ditambahkan.");
        UrlHelper::redirect('/admin/categories');
    }

    public function edit(string|int $id): void
    {
        $category = Category::findById((int) $id);
        if (!$category) {
            FlashHelper::error('Kategori tidak ditemukan.');
            UrlHelper::redirect('/admin/categories');
            return;
        }

        $this->renderAdminView('admin/categories/edit', [
            'title' => 'Edit Kategori: ' . $category['name'],
            'category' => $category,
        ]);
    }

    public function update(string|int $id): void
    {
        $category = Category::findById((int) $id);
        if (!$category) {
            FlashHelper::error('Kategori tidak ditemukan.');
            UrlHelper::redirect('/admin/categories');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($slug)) {
            $slug = Sanitizer::slugify($name);
        } else {
            $slug = Sanitizer::slugify($slug);
        }

        $updateData = [
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];

        if (!empty($_FILES['icon_image']['name'])) {
            $upload = FileUpload::upload($_FILES['icon_image'], 'categories');
            if ($upload['success']) {
                if (!empty($category['icon_image'])) {
                    FileUpload::delete($category['icon_image']);
                }
                $updateData['icon_image'] = $upload['path'];
            }
        }

        Category::updateCategory((int) $id, $updateData);
        FlashHelper::success('Kategori berhasil diperbarui.');
        UrlHelper::redirect('/admin/categories');
    }

    public function delete(string|int $id): void
    {
        $category = Category::findById((int) $id);
        if ($category) {
            if (!empty($category['icon_image'])) {
                FileUpload::delete($category['icon_image']);
            }
            Category::deleteCategory((int) $id);
            FlashHelper::success('Kategori berhasil dihapus.');
        }

        UrlHelper::redirect('/admin/categories');
    }
}
