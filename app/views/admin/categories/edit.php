<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Kategori Produk</h3>
        <p class="text-secondary small mb-0">Perbarui nama atau ikon kategori.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/categories') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border rounded-4 p-4 shadow-sm bg-white" style="max-width: 600px;">
    <form action="<?= UrlHelper::base('admin/categories/update/' . $category['id']) ?>" method="POST" enctype="multipart/form-data">
        <?= CsrfHelper::field() ?>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Kategori *</label>
            <input type="text" name="name" class="form-control" data-slug-source value="<?= e($category['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Slug URL</label>
            <input type="text" name="slug" class="form-control" data-slug-target value="<?= e($category['slug']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Ikon / Gambar Kategori</label>
            <?php if (!empty($category['icon_image'])): ?>
                <div class="mb-2">
                    <img src="<?= UrlHelper::upload($category['icon_image']) ?>" alt="Icon" style="max-height: 40px;">
                </div>
            <?php endif; ?>
            <input type="file" name="icon_image" class="form-control" accept="image/png, image/jpeg, image/webp">
        </div>

        <div class="row g-3 mb-4 align-items-center">
            <div class="col-6">
                <label class="form-label small fw-semibold">Urutan (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="<?= (int) $category['sort_order'] ?>">
            </div>
            <div class="col-6 pt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCat" <?= $category['is_active'] ? 'checked' : '' ?>>
                    <label class="form-check-label small fw-semibold" for="isActiveCat">Aktif</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
        </button>
    </form>
</div>
