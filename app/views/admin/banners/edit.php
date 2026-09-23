<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Banner</h3>
        <p class="text-secondary small mb-0">Perbarui data banner slider.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/banners') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border rounded-4 p-4 shadow-sm bg-white" style="max-width: 700px;">
    <form action="<?= UrlHelper::base('admin/banners/update/' . $banner['id']) ?>" method="POST" enctype="multipart/form-data">
        <?= CsrfHelper::field() ?>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Judul Banner *</label>
            <input type="text" name="title" class="form-control" value="<?= e($banner['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Subtitle / Deskripsi</label>
            <textarea name="subtitle" rows="3" class="form-control"><?= e($banner['subtitle']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Gambar Banner</label>
            <?php if (!empty($banner['image'])): ?>
                <div class="mb-2">
                    <img src="<?= UrlHelper::upload($banner['image']) ?>" alt="Banner" class="rounded border" style="max-height: 120px;">
                </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Teks Tombol</label>
                <input type="text" name="button_text" class="form-control" value="<?= e($banner['button_text']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">URL Tombol</label>
                <input type="text" name="button_url" class="form-control" value="<?= e($banner['button_url']) ?>">
            </div>
        </div>

        <div class="row g-3 mb-4 align-items-center">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="<?= (int) $banner['sort_order'] ?>">
            </div>
            <div class="col-md-6 pt-md-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveSwitch" <?= $banner['is_active'] ? 'checked' : '' ?>>
                    <label class="form-check-label small fw-semibold" for="isActiveSwitch">Aktifkan Banner</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
        </button>
    </form>
</div>
