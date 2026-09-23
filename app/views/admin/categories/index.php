<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Kategori Produk</h3>
        <p class="text-secondary small mb-0">Kelompokkan produk untuk kemudahan navigasi pengunjung.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/categories/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden" style="max-width: 900px;">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th style="width: 70px;">Urutan</th>
                    <th>Nama Kategori</th>
                    <th>Slug URL</th>
                    <th>Jumlah Produk</th>
                    <th>Status</th>
                    <th style="width: 140px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada kategori.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $c): ?>
                        <tr>
                            <td class="fw-bold text-center"><?= (int) $c['sort_order'] ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($c['icon_image'])): ?>
                                        <img src="<?= UrlHelper::upload($c['icon_image']) ?>" alt="<?= e($c['name']) ?>" class="rounded" style="width: 32px; height: 32px; object-fit: contain;">
                                    <?php else: ?>
                                        <div class="bg-light p-1 rounded text-primary"><i class="bi bi-tag-fill"></i></div>
                                    <?php endif; ?>
                                    <span class="fw-bold text-dark"><?= e($c['name']) ?></span>
                                </div>
                            </td>
                            <td><code class="text-primary"><?= e($c['slug']) ?></code></td>
                            <td>
                                <span class="badge rounded-pill text-bg-light border"><?= (int) $c['product_count'] ?> produk</span>
                            </td>
                            <td>
                                <?php if ($c['is_active']): ?>
                                    <span class="badge text-bg-success rounded-pill">Aktif</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary rounded-pill">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= UrlHelper::base('admin/categories/edit/' . $c['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= UrlHelper::base('admin/categories/delete/' . $c['id']) ?>" method="POST" class="d-inline">
                                    <?= CsrfHelper::field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm-delete="Hapus kategori ini beserta asosiasi produknya?" title="Hapus">
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
