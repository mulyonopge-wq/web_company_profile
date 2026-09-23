<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Tambah Kategori Produk</h3>
        <p class="text-secondary small mb-0">Buat kategori produk baru.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/categories') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border rounded-4 p-4 shadow-sm bg-white" style="max-width: 600px;">
    <form action="<?= UrlHelper::base('admin/categories/store') ?>" method="POST" enctype="multipart/form-data">
        <?= CsrfHelper::field() ?>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Kategori *</label>
            <input type="text" name="name" class="form-control" data-slug-source required placeholder="Contoh: Router & Wireless">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Slug URL (Otomatis)</label>
            <input type="text" name="slug" class="form-control" data-slug-target placeholder="router-wireless">
            <small class="text-muted">Digunakan untuk URL: /kategori/nama-kategori</small>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Ikon / Gambar Kategori</label>
            <input type="file" name="icon_image" class="form-control" accept="image/png, image/jpeg, image/webp">
        </div>

        <div class="row g-3 mb-4 align-items-center">
            <div class="col-6">
                <label class="form-label small fw-semibold">Urutan (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="1">
            </div>
            <div class="col-6 pt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCat" checked>
                    <label class="form-check-label small fw-semibold" for="isActiveCat">Aktif</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-floppy-fill me-1"></i> Simpan Kategori
        </button>
    </form>
</div>
