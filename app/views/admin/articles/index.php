<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Artikel & Berita</h3>
        <p class="text-secondary small mb-0">Publikasikan artikel edukasi, tips jaringan, dan update perusahaan.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/articles/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tulis Artikel Baru
        </a>
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
