<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Setting;
use App\Models\Team;

class TeamController extends AdminBaseController
{
    public function index(): void
    {
        $teams = Team::all();
        $teamTitle = Setting::get('company_team_title', 'Tim Manajemen & Pimpinan / Pengurus');
        $teamBadge = Setting::get('company_team_badge', 'Kepemimpinan & Pengurus');
        $teamSubtitle = Setting::get('company_team_subtitle', 'Kelola daftar jajaran pimpinan, dewan direksi, dan pengurus perusahaan yang tampil di halaman profil (Tentang Kami).');

        $this->renderAdminView('admin/teams/index', [
            'title' => $teamTitle,
            'teams' => $teams,
            'teamTitle' => $teamTitle,
            'teamBadge' => $teamBadge,
            'teamSubtitle' => $teamSubtitle,
        ]);
    }

    public function create(): void
    {
        $this->renderAdminView('admin/teams/create', [
            'title' => 'Tambah Anggota Tim / Pimpinan',
        ]);
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name) || empty($position)) {
            FlashHelper::error('Nama lengkap dan Jabatan wajib diisi.');
            UrlHelper::redirect('/admin/teams/tambah');
            return;
        }

        $photoPath = '';
        if (!empty($_FILES['photo']['name'])) {
            $upload = FileUpload::upload($_FILES['photo'], 'teams');
            if ($upload['success']) {
                $photoPath = $upload['path'];
            } else {
                FlashHelper::error('Gagal upload foto: ' . $upload['error']);
                UrlHelper::redirect('/admin/teams/tambah');
                return;
            }
        }

        Team::create([
            'name' => $name,
            'position' => $position,
            'photo' => $photoPath,
            'bio' => $bio,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        FlashHelper::success('Data Tim / Pimpinan berhasil ditambahkan.');
        UrlHelper::redirect('/admin/teams');
    }

    public function edit(string|int $id): void
    {
        $team = Team::findById((int) $id);
        if (!$team) {
            FlashHelper::error('Data anggota tim tidak ditemukan.');
            UrlHelper::redirect('/admin/teams');
            return;
        }

        $this->renderAdminView('admin/teams/edit', [
            'title' => 'Edit Anggota: ' . $team['name'],
            'team' => $team,
        ]);
    }

    public function update(string|int $id): void
    {
        $team = Team::findById((int) $id);
        if (!$team) {
            FlashHelper::error('Data anggota tim tidak ditemukan.');
            UrlHelper::redirect('/admin/teams');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name) || empty($position)) {
            FlashHelper::error('Nama lengkap dan Jabatan wajib diisi.');
            UrlHelper::redirect('/admin/teams/edit/' . $id);
            return;
        }

        $photoPath = $team['photo'];
        if (!empty($_FILES['photo']['name'])) {
            $upload = FileUpload::upload($_FILES['photo'], 'teams');
            if ($upload['success']) {
                if (!empty($team['photo'])) {
                    FileUpload::delete($team['photo']);
                }
                $photoPath = $upload['path'];
            } else {
                FlashHelper::error('Gagal upload foto baru: ' . $upload['error']);
                UrlHelper::redirect('/admin/teams/edit/' . $id);
                return;
            }
        }

        Team::updateTeam((int) $id, [
            'name' => $name,
            'position' => $position,
            'photo' => $photoPath,
            'bio' => $bio,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
        ]);

        FlashHelper::success('Data Tim / Pimpinan berhasil diperbarui.');
        UrlHelper::redirect('/admin/teams');
    }

    public function delete(string|int $id): void
    {
        $team = Team::findById((int) $id);
        if (!$team) {
            FlashHelper::error('Data anggota tim tidak ditemukan.');
            UrlHelper::redirect('/admin/teams');
            return;
        }

        if (!empty($team['photo'])) {
            FileUpload::delete($team['photo']);
        }

        Team::deleteTeam((int) $id);
        FlashHelper::success('Anggota tim "' . $team['name'] . '" berhasil dihapus.');
        UrlHelper::redirect('/admin/teams');
    }

    public function updateSettings(): void
    {
        $title = trim($_POST['company_team_title'] ?? '');
        $badge = trim($_POST['company_team_badge'] ?? '');
        $subtitle = trim($_POST['company_team_subtitle'] ?? '');

        if (!empty($title)) {
            Setting::set('company_team_title', $title, 'company');
        }
        if (!empty($badge)) {
            Setting::set('company_team_badge', $badge, 'company');
        }
        Setting::set('company_team_subtitle', $subtitle, 'company');

        FlashHelper::success('Judul dan kata-kata keterangan tim berhasil diperbarui.');
        UrlHelper::redirect('/admin/teams');
    }
}

