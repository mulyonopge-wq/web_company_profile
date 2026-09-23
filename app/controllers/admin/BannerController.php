<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Banner;

class BannerController extends AdminBaseController
{
    public function index(): void
    {
        $banners = Banner::all();
        $this->renderAdminView('admin/banners/index', [
            'title' => 'Kelola Banner / Slider',
            'banners' => $banners,
        ]);
    }

    public function create(): void
    {
        $this->renderAdminView('admin/banners/create', [
            'title' => 'Tambah Banner Baru',
        ]);
    }

    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $buttonText = trim($_POST['button_text'] ?? 'Lihat Produk');
        $buttonUrl = trim($_POST['button_url'] ?? '/produk');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title)) {
            FlashHelper::error('Judul banner wajib diisi.');
            UrlHelper::redirect('/admin/banners/create');
            return;
        }

        $imagePath = '';
        if (!empty($_FILES['image']['name'])) {
            $upload = FileUpload::upload($_FILES['image'], 'banners');
            if ($upload['success']) {
                $imagePath = $upload['path'];
            } else {
                FlashHelper::error('Gagal upload gambar banner: ' . $upload['error']);
                UrlHelper::redirect('/admin/banners/create');
                return;
            }
        }

        Banner::create([
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => $imagePath,
            'button_text' => $buttonText,
            'button_url' => $buttonUrl,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        FlashHelper::success('Banner baru berhasil ditambahkan.');
        UrlHelper::redirect('/admin/banners');
    }

    public function edit(string|int $id): void
    {
        $banner = Banner::findById((int) $id);
        if (!$banner) {
            FlashHelper::error('Banner tidak ditemukan.');
            UrlHelper::redirect('/admin/banners');
            return;
        }

        $this->renderAdminView('admin/banners/edit', [
            'title' => 'Edit Banner: ' . $banner['title'],
            'banner' => $banner,
        ]);
    }

    public function update(string|int $id): void
    {
        $banner = Banner::findById((int) $id);
        if (!$banner) {
            FlashHelper::error('Banner tidak ditemukan.');
            UrlHelper::redirect('/admin/banners');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $buttonText = trim($_POST['button_text'] ?? 'Lihat Produk');
        $buttonUrl = trim($_POST['button_url'] ?? '/produk');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $updateData = [
            'title' => $title,
            'subtitle' => $subtitle,
            'button_text' => $buttonText,
            'button_url' => $buttonUrl,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ];

        if (!empty($_FILES['image']['name'])) {
            $upload = FileUpload::upload($_FILES['image'], 'banners');
            if ($upload['success']) {
                if (!empty($banner['image'])) {
                    FileUpload::delete($banner['image']);
                }
                $updateData['image'] = $upload['path'];
            } else {
                FlashHelper::warning('Gagal upload gambar baru: ' . $upload['error']);
            }
        }

        Banner::updateBanner((int) $id, $updateData);
        FlashHelper::success('Banner berhasil diperbarui.');
        UrlHelper::redirect('/admin/banners');
    }

    public function delete(string|int $id): void
    {
        $banner = Banner::findById((int) $id);
        if ($banner) {
            if (!empty($banner['image'])) {
                FileUpload::delete($banner['image']);
            }
            Banner::deleteBanner((int) $id);
            FlashHelper::success('Banner berhasil dihapus.');
        }

        UrlHelper::redirect('/admin/banners');
    }
}
