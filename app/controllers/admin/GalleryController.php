<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Gallery;
use App\Models\Setting;

class GalleryController extends AdminBaseController
{
    public function index(): void
    {
        $galleries = Gallery::all();
        $galleryBadge = Setting::get('gallery_page_badge', 'Dokumentasi & Portofolio');
        $galleryTitle = Setting::get('gallery_page_title', 'Galeri Fasilitas & Kegiatan Perusahaan');
        $gallerySubtitle = Setting::get('gallery_page_subtitle', 'Dokumentasi laboratorium pengujian perangkat, fasilitas gudang logistik, serta implementasi proyek instalasi jaringan bersama klien kami.');

        $this->renderAdminView('admin/galleries/index', [
            'title' => 'Kelola Galeri Foto',
            'galleries' => $galleries,
            'galleryBadge' => $galleryBadge,
            'galleryTitle' => $galleryTitle,
            'gallerySubtitle' => $gallerySubtitle,
        ]);
    }

    public function create(): void
    {
        $this->renderAdminView('admin/galleries/create', [
            'title' => 'Unggah Foto Galeri',
        ]);
    }

    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $category = trim($_POST['category'] ?? 'Kegiatan');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title) || empty($_FILES['image']['name'])) {
            FlashHelper::error('Judul dan file gambar wajib diisi.');
            UrlHelper::redirect('/admin/galleries/create');
            return;
        }

        $upload = FileUpload::upload($_FILES['image'], 'galleries');
        if (!$upload['success']) {
            FlashHelper::error('Gagal upload gambar: ' . $upload['error']);
            UrlHelper::redirect('/admin/galleries/create');
            return;
        }

        Gallery::create([
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'image' => $upload['path'],
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        FlashHelper::success('Foto berhasil ditambahkan ke galeri.');
        UrlHelper::redirect('/admin/galleries');
    }

    public function delete(string|int $id): void
    {
        $gallery = Gallery::findById((int) $id);
        if ($gallery) {
            if (!empty($gallery['image'])) {
                FileUpload::delete($gallery['image']);
            }
            Gallery::deleteGallery((int) $id);
            FlashHelper::success('Foto galeri berhasil dihapus.');
        }

        UrlHelper::redirect('/admin/galleries');
    }

    public function updateSettings(): void
    {
        $badge = trim($_POST['gallery_page_badge'] ?? '');
        $title = trim($_POST['gallery_page_title'] ?? '');
        $subtitle = trim($_POST['gallery_page_subtitle'] ?? '');

        Setting::set('gallery_page_badge', $badge, 'gallery');
        if (!empty($title)) {
            Setting::set('gallery_page_title', $title, 'gallery');
        }
        Setting::set('gallery_page_subtitle', $subtitle, 'gallery');

        FlashHelper::success('Judul dan kata-kata halaman galeri berhasil diperbarui.');
        UrlHelper::redirect('/admin/galleries');
    }
}
