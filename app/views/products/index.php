<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyWa = $settings['company_whatsapp'] ?? '081234567890';
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base('produk') ?>">Produk</a></li>
                <?php if ($selectedCategory): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($selectedCategory['name']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item active" aria-current="page">Semua Produk</li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card border rounded-4 p-3 shadow-sm mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Produk</h5>

                <form action="<?= UrlHelper::base('produk') ?>" method="GET">
                    <!-- Search Input -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Cari Nama / SKU</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="form-control" placeholder="Kata kunci..." value="<?= e($filters['q'] ?? '') ?>">
                            <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kategori</label>
                        <div class="d-flex flex-column gap-1 small">
                            <a href="<?= UrlHelper::base('produk') ?>" class="text-decoration-none py-1 px-2 rounded <?= empty($filters['category_slug']) ? 'bg-primary text-white fw-bold' : 'text-dark hover-bg-light' ?>">
                                Semua Kategori
                            </a>
                            <?php foreach ($categories as $cat): ?>
                                <a href="<?= UrlHelper::base('kategori/' . $cat['slug']) ?>" class="text-decoration-none py-1 px-2 rounded d-flex justify-content-between align-items-center <?= ($filters['category_slug'] ?? '') === $cat['slug'] ? 'bg-primary text-white fw-bold' : 'text-dark' ?>">
                                    <span><?= e($cat['name']) ?></span>
                                    <span class="badge rounded-pill bg-light text-secondary"><?= (int) $cat['product_count'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if (!empty($filters['category_slug'])): ?>
                        <input type="hidden" name="kategori" value="<?= e($filters['category_slug']) ?>">
                    <?php endif; ?>

                    <!-- Price Filter -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Rentang Harga (Rp)</label>
                        <div class="row g-1">
                            <div class="col-6">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?= e($filters['min_price'] ?? '') ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Maks" value="<?= e($filters['max_price'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Sorting -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Urutkan</label>
                        <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Terbaru</option>
                            <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Harga: Rendah ke Tinggi</option>
                            <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Harga: Tinggi ke Rendah</option>
                            <option value="name_asc" <?= ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : '' ?>>Nama: A - Z</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill">Terapkan Filter</button>
                        <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Reset Filter</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Product Listing Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0 text-dark"><?= e($title) ?></h4>
                <small class="text-secondary">Menampilkan <?= count($products) ?> dari <?= (int) $total ?> produk</small>
            </div>

            <?php if (empty($products)): ?>
                <div class="card border rounded-4 p-5 text-center my-4">
                    <i class="bi bi-box-seam text-secondary display-4 mb-3"></i>
                    <h5 class="fw-bold text-dark">Tidak Ada Produk Ditemukan</h5>
                    <p class="text-secondary small mb-3">Coba ubah kata kunci pencarian atau reset filter kategori.</p>
                    <div>
                        <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-primary rounded-pill btn-sm px-4">Lihat Semua Produk</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($products as $prod):
                        $effectivePrice = ($prod['discount_price'] > 0) ? $prod['discount_price'] : $prod['price'];
                        $hasDiscount = ($prod['discount_price'] > 0);
                        $productUrl = UrlHelper::base('produk/' . $prod['slug']);
                    ?>
                        <div class="col-xl-4 col-md-6">
                            <div class="product-card">
                                <div class="product-img-wrap">
                                    <a href="<?= $productUrl ?>" class="d-block w-100 h-100 text-center">
                                        <img src="<?= UrlHelper::upload($prod['main_image'], 'assets/images/no-image.png') ?>" alt="<?= e($prod['name']) ?>" loading="lazy">
                                    </a>
                                    <?php if ($hasDiscount): ?>
                                        <span class="product-badge-discount">HEMAT</span>
                                    <?php endif; ?>
                                    <?php if (!empty($prod['is_featured'])): ?>
                                        <span class="product-badge-featured"><i class="bi bi-star-fill text-warning"></i> Unggulan</span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-body">
                                    <span class="product-category"><?= e($prod['category_name'] ?? 'Networking') ?></span>
                                    <h6 class="product-title">
                                        <a href="<?= $productUrl ?>"><?= e($prod['name']) ?></a>
                                    </h6>
                                    <div class="small text-muted mb-2">SKU: <?= e($prod['sku']) ?></div>

                                    <div class="product-price-box mb-3">
                                        <span class="product-price-current"><?= Sanitizer::formatRupiah($effectivePrice) ?></span>
                                        <?php if ($hasDiscount): ?>
                                            <span class="product-price-old"><?= Sanitizer::formatRupiah($prod['price']) ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <form action="<?= UrlHelper::base('keranjang/tambah') ?>" method="POST" class="ajax-add-to-cart flex-grow-1">
                                            <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-pill" <?= ($prod['stock'] <= 0) ? 'disabled' : '' ?>>
                                                <i class="bi bi-cart-plus me-1"></i> Keranjang
                                            </button>
                                        </form>
                                        <a href="<?= WhatsAppHelper::getProductInquiryLink($companyWa, $prod['name'], $effectivePrice, $productUrl) ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3" title="Tanya Produk via WhatsApp">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-5" aria-label="Navigasi halaman produk">
                        <ul class="pagination justify-content-center">
                            <?php
                            $queryParams = $_GET;
                            for ($i = 1; $i <= $totalPages; $i++):
                                $queryParams['page'] = $i;
                                $pageUrl = UrlHelper::base('produk') . '?' . http_build_query($queryParams);
                            ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= $pageUrl ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
