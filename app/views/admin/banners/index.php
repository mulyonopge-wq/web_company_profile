<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Banner / Slider</h3>
        <p class="text-secondary small mb-0">Atur hero slider pada halaman utama website.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/banners/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Banner
        </a>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th style="width: 60px;">Urutan</th>
                    <th style="width: 120px;">Gambar</th>
                    <th>Judul & Subtitle</th>
                    <th>Tombol</th>
                    <th>Status</th>
                    <th style="width: 140px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($banners)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada banner tersimpan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($banners as $b): ?>
                        <tr>
                            <td class="fw-bold text-center"><?= (int) $b['sort_order'] ?></td>
                            <td>
                                <?php if (!empty($b['image'])): ?>
                                    <img src="<?= UrlHelper::upload($b['image']) ?>" alt="<?= e($b['title']) ?>" class="rounded border" style="width: 100px; height: 55px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light border rounded text-center text-muted small py-2">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= e($b['title']) ?></div>
                                <small class="text-secondary"><?= e(Sanitizer::truncate($b['subtitle'] ?? '', 80)) ?></small>
                            </td>
                            <td>
                                <span class="badge text-bg-light border text-dark"><?= e($b['button_text']) ?></span>
                                <small class="text-muted d-block"><?= e($b['button_url']) ?></small>
                            </td>
                            <td>
                                <?php if ($b['is_active']): ?>
                                    <span class="badge text-bg-success rounded-pill">Aktif</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary rounded-pill">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= UrlHelper::base('admin/banners/edit/' . $b['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= UrlHelper::base('admin/banners/delete/' . $b['id']) ?>" method="POST" class="d-inline">
                                    <?= CsrfHelper::field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm-delete="Hapus banner ini?" title="Hapus">
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
