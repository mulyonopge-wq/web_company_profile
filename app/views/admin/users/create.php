<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Tambah Administrator Baru</h3>
        <p class="text-secondary small mb-0">Buat akun admin pengelola baru.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/users') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border rounded-4 p-4 shadow-sm bg-white" style="max-width: 600px;">
    <form action="<?= UrlHelper::base('admin/users/store') ?>" method="POST">
        <?= CsrfHelper::field() ?>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" required placeholder="Nama lengkap staf/admin">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Username Login *</label>
            <input type="text" name="username" class="form-control" required placeholder="username_login">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Alamat Email *</label>
            <input type="email" name="email" class="form-control" required placeholder="admin@perusahaan.com">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Password *</label>
            <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold">Role / Hak Akses</label>
            <select name="role" class="form-select">
                <option value="admin">Administrator Penuh</option>
                <option value="staff">Staf Operasional</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-person-check-fill me-1"></i> Simpan Pengguna
        </button>
    </form>
</div>
