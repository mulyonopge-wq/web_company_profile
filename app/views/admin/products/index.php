<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Produk Katalog</h3>
        <p class="text-secondary small mb-0">Total <?= (int) $total ?> produk terdaftar di sistem.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/products/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="card border rounded-4 p-3 shadow-sm bg-white mb-4">
    <form action="<?= UrlHelper::base('admin/products') ?>" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="q" class="form-control" placeholder="Cari nama produk / SKU..." value="<?= e($filters['q'] ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">-- Semua Kategori --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (($filters['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 flex-grow-1">Filter</button>
            <a href="<?= UrlHelper::base('admin/products') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="card border rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th style="width: 80px;">Foto</th>
                    <th>Nama Produk & SKU</th>
                    <th>Kategori</th>
                    <th>Harga & Diskon</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="width: 140px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data produk yang ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $p):
                        $effectivePrice = ($p['discount_price'] > 0) ? $p['discount_price'] : $p['price'];
                    ?>
                        <tr>
                            <td>
                                <img src="<?= UrlHelper::upload($p['main_image'], 'assets/images/no-image.png') ?>" alt="<?= e($p['name']) ?>" class="rounded border" style="width: 60px; height: 60px; object-fit: contain;">
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-1">
                                    <a href="<?= UrlHelper::base('produk/' . $p['slug']) ?>" target="_blank" class="text-dark text-decoration-none">
                                        <?= e($p['name']) ?>
                                    </a>
                                </div>
                                <small class="text-muted">SKU: <code><?= e($p['sku']) ?></code> &bull; <?= (int)$p['weight'] ?> gram</small>
                                <?php if (!empty($p['is_featured'])): ?>
                                    <span class="badge text-bg-warning ms-1 small"><i class="bi bi-star-fill"></i> Unggulan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge text-bg-light border"><?= e($p['category_name'] ?? '-') ?></span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= Sanitizer::formatRupiah($effectivePrice) ?></div>
                                <?php if ($p['discount_price'] > 0): ?>
                                    <small class="text-muted text-decoration-line-through"><?= Sanitizer::formatRupiah($p['price']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['stock'] > 10): ?>
                                    <span class="badge text-bg-success rounded-pill"><?= (int) $p['stock'] ?> unit</span>
                                <?php elseif ($p['stock'] > 0): ?>
                                    <span class="badge text-bg-warning rounded-pill"><?= (int) $p['stock'] ?> unit</span>
                                <?php else: ?>
                                    <span class="badge text-bg-danger rounded-pill">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['is_active']): ?>
                                    <span class="badge text-bg-success rounded-pill">Aktif</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary rounded-pill">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= UrlHelper::base('admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= UrlHelper::base('admin/products/delete/' . $p['id']) ?>" method="POST" class="d-inline">
                                    <?= CsrfHelper::field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm-delete="Hapus produk ini secara permanen?" title="Hapus">
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

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <nav class="mt-4">
        <ul class="pagination pagination-sm justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++):
                $pParams = $_GET;
                $pParams['page'] = $i;
            ?>
                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= UrlHelper::base('admin/products') . '?' . http_build_query($pParams) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>
