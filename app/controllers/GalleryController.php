<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Gallery;
use App\Models\Setting;

class GalleryController extends BaseController
{
    public function index(): void
    {
        $galleries = Gallery::getActive();
        $categories = Gallery::getCategories();

        $this->renderView('gallery/index', [
            'title' => Setting::get('gallery_page_title', 'Galeri Fasilitas & Kegiatan Perusahaan'),
            'galleries' => $galleries,
            'categories' => $categories,
            'galleryBadge' => Setting::get('gallery_page_badge', 'Dokumentasi & Portofolio'),
            'galleryTitle' => Setting::get('gallery_page_title', 'Galeri Fasilitas & Kegiatan Perusahaan'),
            'gallerySubtitle' => Setting::get('gallery_page_subtitle', 'Dokumentasi laboratorium pengujian perangkat, fasilitas gudang logistik, serta implementasi proyek instalasi jaringan bersama klien kami.'),
        ]);
    }
}
