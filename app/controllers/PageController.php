<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Team;

class PageController extends BaseController
{
    public function about(): void
    {
        $page = Page::findBySlug('tentang-kami');
        $teams = Team::getActive();
        $this->renderView('pages/about', [
            'title' => 'Tentang Kami',
            'page' => $page,
            'teams' => $teams,
        ]);
    }

    public function contact(): void
    {
        $contactBadge = Setting::get('contact_page_badge', 'Bantuan & Layanan');
        $contactTitle = Setting::get('contact_page_title', 'Hubungi Kami');
        $contactSubtitle = Setting::get('contact_page_subtitle', 'Kami siap membantu menjawab kebutuhan teknologi jaringan, stok produk, serta permintaan penawaran harga resmi perusahaan Anda.');

        $this->renderView('pages/contact', [
            'title' => $contactTitle,
            'contactBadge' => $contactBadge,
            'contactTitle' => $contactTitle,
            'contactSubtitle' => $contactSubtitle,
        ]);
    }

    public function contactSubmit(): void
    {
        // Simple contact form submission confirmation
        $name = $_POST['name'] ?? '';
        FlashHelper::success("Terima kasih, {$name}! Pesan Anda telah kami terima. Tim kami akan segera menghubungi Anda.");
        UrlHelper::redirect('/kontak');
    }

    public function faq(): void
    {
        $page = Page::findBySlug('faq');
        $this->renderView('pages/faq', [
            'title' => $page['title'] ?? 'Pertanyaan Umum (FAQ)',
            'page' => $page,
        ]);
    }

    public function privacy(): void
    {
        $page = Page::findBySlug('kebijakan-privasi');
        $this->renderView('pages/privacy', [
            'title' => $page['title'] ?? 'Kebijakan Privasi',
            'page' => $page,
        ]);
    }

    public function terms(): void
    {
        $page = Page::findBySlug('syarat-ketentuan');
        $this->renderView('pages/terms', [
            'title' => $page['title'] ?? 'Syarat & Ketentuan',
            'page' => $page,
        ]);
    }
}
