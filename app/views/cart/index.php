<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base('produk') ?>">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <h1 class="fw-bold text-dark mb-4">Keranjang Belanja</h1>

    <?php if (empty($cart)): ?>
        <div class="card border rounded-4 p-5 text-center my-4">
            <i class="bi bi-cart-x text-secondary display-3 mb-3"></i>
            <h4 class="fw-bold text-dark">Keranjang Belanja Masih Kosong</h4>
            <p class="text-secondary small mb-4">Anda belum menambahkan produk apa pun ke keranjang belanja.</p>
            <div>
                <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-grid me-1"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <!-- Items Table -->
            <div class="col-lg-8">
                <div class="card border rounded-4 overflow-hidden shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th style="min-width: 280px;">Produk</th>
                                    <th>Harga</th>
                                    <th style="width: 130px;">Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="<?= UrlHelper::upload($item['image'], 'assets/images/no-image.png') ?>" alt="<?= e($item['name']) ?>" class="rounded-3 border" style="width: 60px; height: 60px; object-fit: contain;">
                                                <div>
                                                    <a href="<?= UrlHelper::base('produk/' . $item['slug']) ?>" class="fw-semibold text-dark text-decoration-none small d-block">
                                                        <?= e($item['name']) ?>
                                                    </a>
                                                    <small class="text-muted">SKU: <?= e($item['sku']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="small fw-semibold"><?= Sanitizer::formatRupiah($item['price']) ?></span>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm text-center cart-qty-input fw-bold"
                                                   data-product-id="<?= $item['product_id'] ?>"
                                                   data-update-url="<?= UrlHelper::base('keranjang/update') ?>"
                                                   value="<?= (int) $item['quantity'] ?>" min="1" max="99">
                                        </td>
                                        <td>
                                            <span id="subtotal-<?= $item['product_id'] ?>" class="fw-bold text-danger small">
                                                <?= Sanitizer::formatRupiah($item['price'] * $item['quantity']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= UrlHelper::base('keranjang/hapus/' . $item['product_id']) ?>" class="text-danger p-2" title="Hapus dari keranjang" onclick="return confirm('Hapus produk ini dari keranjang?');">
                                                <i class="bi bi-trash3-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
                        <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
                    </a>
                    <a href="<?= UrlHelper::base('keranjang/kosongkan') ?>" class="btn btn-outline-danger rounded-pill btn-sm px-3" onclick="return confirm('Kosongkan semua isi keranjang belanja?');">
                        <i class="bi bi-trash3 me-1"></i> Kosongkan Keranjang
                    </a>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border rounded-4 p-4 shadow-sm">
                    <h5 class="fw-bold mb-3 text-dark">Ringkasan Pesanan</h5>

                    <div class="d-flex justify-content-between small text-secondary mb-2">
                        <span>Total Item</span>
                        <span class="fw-bold text-dark"><?= (int) $cart_count ?> item</span>
                    </div>

                    <div class="d-flex justify-content-between small text-secondary mb-3">
                        <span>Subtotal Produk</span>
                        <span id="cart-grand-total" class="fw-bold text-dark fs-6"><?= Sanitizer::formatRupiah($subtotal) ?></span>
                    </div>

                    <div class="border-top pt-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark">Total Estimasi</span>
                            <span class="fs-5 fw-bold text-danger"><?= Sanitizer::formatRupiah($subtotal) ?></span>
                        </div>
                        <small class="text-muted d-block mt-1">* Belum termasuk ongkos kirim (dihitung oleh tim sales via WhatsApp).</small>
                    </div>

                    <a href="<?= UrlHelper::base('checkout') ?>" class="btn btn-primary rounded-pill py-2 w-100 fw-semibold">
                        Lanjut ke Checkout <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
