<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Artikel</h3>
        <p class="text-secondary small mb-0">Perbarui artikel: <?= e($article['title']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= UrlHelper::base('artikel/' . $article['slug']) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Pratinjau
        </a>
        <a href="<?= UrlHelper::base('admin/articles') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form action="<?= UrlHelper::base('admin/articles/update/' . $article['id']) ?>" method="POST" enctype="multipart/form-data">
    <?= CsrfHelper::field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Judul Artikel *</label>
                    <input type="text" name="title" class="form-control" data-slug-source value="<?= e($article['title']) ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Kategori Artikel</label>
                        <input type="text" name="category" class="form-control" value="<?= e($article['category']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Slug URL (SEO)</label>
                        <input type="text" name="slug" class="form-control" data-slug-target value="<?= e($article['slug']) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Isi Konten Artikel * (Mendukung HTML)</label>
                    <textarea name="content" rows="12" class="form-control" required><?= e($article['content']) ?></textarea>
                </div>
            </div>

            <!-- SEO Meta Tags -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-search me-1 text-primary"></i>Pengaturan SEO Artikel</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= e($article['meta_title'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="form-control"><?= e($article['meta_description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control" value="<?= e($article['meta_keywords'] ?? '') ?>">
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Publikasi & Gambar</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Status Artikel</label>
                    <select name="status" class="form-select">
                        <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Gambar Utama / Thumbnail</label>
                    <?php if (!empty($article['thumbnail'])): ?>
                        <div class="mb-2">
                            <img src="<?= UrlHelper::upload($article['thumbnail']) ?>" alt="Thumbnail" class="rounded border" style="max-height: 120px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="thumbnail" class="form-control" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah thumbnail.</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                        <i class="bi bi-floppy-fill me-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
