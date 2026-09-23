<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Setting;

class CompanyController extends AdminBaseController
{
    public function index(): void
    {
        $settings = Setting::getAllSettings();
        $this->renderAdminView('admin/company/edit', [
            'title' => 'Kelola Profil Perusahaan',
            'company' => $settings,
        ]);
    }

    public function update(): void
    {
        $fields = [
            'company_name',
            'company_slogan',
            'company_short_description',
            'company_long_description',
            'company_established_year',
            'company_address',
            'company_whatsapp',
            'company_phone',
            'company_email',
            'company_maps_embed',
            'company_hours',
            'company_vision',
            'company_mission',
            'company_values',
            'company_advantages',
            'company_badge_title',
            'company_badge_desc',
            'company_team_title',
            'company_team_badge',
            'company_team_subtitle',
            'contact_page_badge',
            'contact_page_title',
            'contact_page_subtitle',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                Setting::set($field, trim($_POST[$field]), 'company');
            }
        }

        // Handle Logo upload
        if (!empty($_FILES['site_logo']['name'])) {
            $upload = FileUpload::upload($_FILES['site_logo'], 'settings');
            if ($upload['success']) {
                $oldLogo = Setting::get('site_logo');
                if ($oldLogo) {
                    FileUpload::delete($oldLogo);
                }
                Setting::set('site_logo', $upload['path'], 'general');
            } else {
                FlashHelper::warning('Gagal upload logo: ' . $upload['error']);
            }
        }

        // Handle Favicon upload
        if (!empty($_FILES['site_favicon']['name'])) {
            $upload = FileUpload::upload($_FILES['site_favicon'], 'settings');
            if ($upload['success']) {
                $oldFav = Setting::get('site_favicon');
                if ($oldFav) {
                    FileUpload::delete($oldFav);
                }
                Setting::set('site_favicon', $upload['path'], 'general');
            } else {
                FlashHelper::warning('Gagal upload favicon: ' . $upload['error']);
            }
        }

        // Handle About Page Image upload
        if (!empty($_FILES['company_about_image']['name'])) {
            $upload = FileUpload::upload($_FILES['company_about_image'], 'company');
            if ($upload['success']) {
                $oldAbout = Setting::get('company_about_image');
                if ($oldAbout) {
                    FileUpload::delete($oldAbout);
                }
                Setting::set('company_about_image', $upload['path'], 'company');
            } else {
                FlashHelper::warning('Gagal upload foto Tentang Kami: ' . $upload['error']);
            }
        }

        FlashHelper::success('Profil Perusahaan berhasil diperbarui.');
        UrlHelper::redirect('/admin/company');
    }
}
