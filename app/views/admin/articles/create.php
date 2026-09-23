<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Tulis Artikel Baru</h3>
        <p class="text-secondary small mb-0">Publikasikan wawasan atau pengumuman resmi perusahaan.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/articles') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="<?= UrlHelper::base('admin/articles/store') ?>" method="POST" enctype="multipart/form-data">
    <?= CsrfHelper::field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Judul Artikel *</label>
                    <input type="text" name="title" class="form-control" data-slug-source required placeholder="Contoh: 5 Alasan Mengapa Kantor Anda Wajib Menggunakan MikroTik">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kategori Artikel</label>
                        <input type="text" name="category" class="form-control" value="Tips & Edukasi" placeholder="Contoh: Tips & Edukasi / Berita">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Slug URL (SEO)</label>
                        <input type="text" name="slug" class="form-control" data-slug-target placeholder="5-alasan-menggunakan-mikrotik">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Isi Konten Artikel * (Mendukung HTML)</label>
                    <textarea name="content" rows="12" class="form-control" required placeholder="Tuliskan isi artikel Anda di sini..."></textarea>
                </div>
            </div>

            <!-- SEO Meta Tags -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-search me-1 text-primary"></i>Pengaturan SEO Artikel</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" placeholder="Biarkan kosong jika sama dengan judul">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-control" placeholder="Ringkasan 150 karakter untuk pencarian Google..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" placeholder="mikrotik, router kantor, bandwidth management">
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Publikasi & Gambar</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Status Artikel</label>
                    <select name="status" class="form-select">
                        <option value="published">Langsung Terbitkan (Published)</option>
                        <option value="draft">Simpan sebagai Draft</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Gambar Utama / Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Rasio 16:9, min. 800x450px.</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                        <i class="bi bi-floppy-fill me-2"></i> Simpan Artikel
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
