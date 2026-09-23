<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Pengguna Admin</h3>
        <p class="text-secondary small mb-0">Perbarui profil atau kata sandi pengguna.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/users') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border rounded-4 p-4 shadow-sm bg-white" style="max-width: 600px;">
    <form action="<?= UrlHelper::base('admin/users/update/' . $user['id']) ?>" method="POST">
        <?= CsrfHelper::field() ?>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap *</label>
            <input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Username Login</label>
            <input type="text" class="form-control bg-light" value="<?= e($user['username']) ?>" disabled readonly>
            <small class="text-muted">Username tidak dapat diubah.</small>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Alamat Email *</label>
            <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Password Baru (Opsional)</label>
            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengganti password">
        </div>

        <div class="mb-4">
            <label class="form-label small fw-semibold">Role / Hak Akses</label>
            <select name="role" class="form-select">
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator Penuh</option>
                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staf Operasional</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
        </button>
    </form>
</div>
