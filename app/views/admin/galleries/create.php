<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Unggah Foto Galeri</h3>
        <p class="text-secondary small mb-0">Tambahkan dokumentasi foto baru.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/galleries') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border rounded-4 p-4 shadow-sm bg-white" style="max-width: 600px;">
    <form action="<?= UrlHelper::base('admin/galleries/store') ?>" method="POST" enctype="multipart/form-data">
        <?= CsrfHelper::field() ?>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Judul Foto *</label>
            <input type="text" name="title" class="form-control" required placeholder="Contoh: Lab Pengujian Router">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Kategori Dokumentasi</label>
            <input type="text" name="category" class="form-control" value="Fasilitas" placeholder="Contoh: Fasilitas / Proyek / Kegiatan">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">File Gambar *</label>
            <input type="file" name="image" class="form-control" required accept="image/png, image/jpeg, image/webp">
        </div>

        <div class="mb-3">
            <label class="form-label small fw-semibold">Deskripsi Singkat</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Penjelasan singkat mengenai foto..."></textarea>
        </div>

        <div class="row g-3 mb-4 align-items-center">
            <div class="col-6">
                <label class="form-label small fw-semibold">Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="1">
            </div>
            <div class="col-6 pt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActiveGal" checked>
                    <label class="form-check-label small fw-semibold" for="isActiveGal">Aktif</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-cloud-upload-fill me-1"></i> Unggah Foto
        </button>
    </form>
</div>
