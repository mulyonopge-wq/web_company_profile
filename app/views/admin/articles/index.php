<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Artikel & Berita</h3>
        <p class="text-secondary small mb-0">Publikasikan artikel edukasi, tips jaringan, dan update perusahaan.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#editArticleTextsCollapse" aria-expanded="false" aria-controls="editArticleTextsCollapse">
            <i class="bi bi-pencil-square me-1"></i> Edit Judul & Keterangan
        </button>
        <a href="<?= UrlHelper::base('artikel') ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Lihat Halaman Artikel
        </a>
        <a href="<?= UrlHelper::base('admin/articles/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
        </a>
    </div>
</div>

<!-- Form Edit Kata-kata Judul & Keterangan Halaman Artikel -->
<div class="collapse mb-4" id="editArticleTextsCollapse">
    <div class="card border rounded-4 p-4 shadow-sm bg-white">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-fonts fs-6"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Ubah Kata-kata Judul & Keterangan Artikel</h5>
                <small class="text-secondary">Kata-kata ini akan tampil di bagian pembuka / header halaman Artikel (<code>/artikel</code>).</small>
            </div>
        </div>

        <form action="<?= UrlHelper::base('admin/articles/settings') ?>" method="POST">
            <?= CsrfHelper::field() ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Label / Badge Seksi</label>
                    <input type="text" name="article_page_badge" class="form-control" value="<?= e($articleBadge ?? 'Pusat Edukasi & Berita') ?>" placeholder="Contoh: Pusat Edukasi & Berita">
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Judul Halaman Artikel *</label>
                    <input type="text" name="article_page_title" class="form-control" value="<?= e($articleTitle ?? 'Artikel & Tips Teknologi Jaringan') ?>" placeholder="Contoh: Artikel & Tips Teknologi Jaringan" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Kata-kata Keterangan / Deskripsi Sub-judul</label>
                    <textarea name="article_page_subtitle" rows="3" class="form-control" placeholder="Tuliskan kata-kata pengantar / ulasan di bawah judul..."><?= e($articleSubtitle ?? 'Dapatkan wawasan seputar konfigurasi router, optimasi bandwidth kantor, keamanan jaringan, dan ulasan perangkat IT terbaru.') ?></textarea>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light btn-sm border rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#editArticleTextsCollapse">Tutup</button>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan Teks
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th style="width: 80px;">Thumbnail</th>
                    <th>Judul Artikel</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Tanggal Terbit</th>
                    <th style="width: 140px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada artikel yang ditulis.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articles as $a): ?>
                        <tr>
                            <td>
                                <?php if (!empty($a['thumbnail'])): ?>
                                    <img src="<?= UrlHelper::upload($a['thumbnail']) ?>" alt="<?= e($a['title']) ?>" class="rounded border" style="width: 65px; height: 45px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light border rounded text-center text-muted small py-2">No Img</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= UrlHelper::base('artikel/' . $a['slug']) ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                    <?= e($a['title']) ?>
                                </a>
                                <small class="text-muted d-block">Slug: <code><?= e($a['slug']) ?></code></small>
                            </td>
                            <td>
                                <span class="badge text-bg-light border"><?= e($a['category']) ?></span>
                            </td>
                            <td>
                                <?php if ($a['status'] === 'published'): ?>
                                    <span class="badge text-bg-success rounded-pill">Published</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary rounded-pill">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <?= !empty($a['published_at']) ? date('d M Y', strtotime($a['published_at'])) : '-' ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= UrlHelper::base('admin/articles/edit/' . $a['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= UrlHelper::base('admin/articles/delete/' . $a['id']) ?>" method="POST" class="d-inline">
                                    <?= CsrfHelper::field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm-delete="Hapus artikel ini?" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
