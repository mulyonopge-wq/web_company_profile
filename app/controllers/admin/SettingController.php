<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Page;
use App\Models\Setting;

class SettingController extends AdminBaseController
{
    public function index(): void
    {
        $settings = Setting::getAllSettings();
        $pages = Page::all();

        $this->renderAdminView('admin/settings/index', [
            'title' => 'Pengaturan Website & Halaman',
            'settings' => $settings,
            'pages' => $pages,
        ]);
    }

    public function update(): void
    {
        $fields = [
            'site_name',
            'site_tagline',
            'site_description',
            'site_keywords',
            'social_facebook',
            'social_instagram',
            'social_tiktok',
            'social_youtube',
            'footer_text',
            'footer_copyright',
            'contact_page_badge',
            'contact_page_title',
            'contact_page_subtitle',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                Setting::set($field, trim($_POST[$field]), 'general');
            }
        }

        FlashHelper::success('Pengaturan website berhasil disimpan.');
        UrlHelper::redirect('/admin/settings');
    }

    public function updatePage(): void
    {
        $slug = trim($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDescription = trim($_POST['meta_description'] ?? '');

        if (!empty($slug)) {
            Page::updateBySlug($slug, [
                'content' => $content,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDescription,
            ]);
            FlashHelper::success("Konten halaman \"{$slug}\" berhasil diperbarui.");
        }

        UrlHelper::redirect('/admin/settings#pages');
    }
}
