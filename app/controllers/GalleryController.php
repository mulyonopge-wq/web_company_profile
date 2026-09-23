<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Gallery;

class GalleryController extends BaseController
{
    public function index(): void
    {
        $galleries = Gallery::getActive();
        $categories = Gallery::getCategories();

        $this->renderView('gallery/index', [
            'title' => 'Galeri Perusahaan & Dokumentasi Proyek',
            'galleries' => $galleries,
            'categories' => $categories,
        ]);
    }
}
