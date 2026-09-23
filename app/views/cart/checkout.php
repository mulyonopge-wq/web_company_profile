<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base('keranjang') ?>">Keranjang</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="text-center mb-4">
        <h1 class="fw-bold text-dark">Checkout Pemesanan</h1>
        <p class="text-secondary small">Lengkapi data pengiriman Anda. Pesanan akan otomatis tersimpan dan diteruskan ke WhatsApp Admin Sales kami.</p>
    </div>

    <div class="row g-4">
        <!-- Customer Details Form -->
        <div class="col-lg-7">
            <div class="card border rounded-4 p-4 shadow-sm">
                <h4 class="fw-bold text-dark mb-3"><i class="bi bi-person-lines-fill text-primary me-2"></i>Data Pembeli & Pengiriman</h4>

                <form action="<?= UrlHelper::base('checkout/proses') ?>" method="POST">
                    <?= CsrfHelper::field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap *</label>
                        <input type="text" name="customer_name" class="form-control rounded-3" required placeholder="Contoh: Budi Santoso / PT Maju Terus">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nomor WhatsApp Aktif *</label>
                            <input type="text" name="customer_phone" class="form-control rounded-3" required placeholder="Contoh: 081234567890">
                            <small class="text-muted">Untuk konfirmasi pengiriman & nomor resi.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Alamat Email (Opsional)</label>
                            <input type="email" name="customer_email" class="form-control rounded-3" placeholder="email@domain.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Pengiriman Lengkap *</label>
                        <textarea name="customer_address" rows="3" class="form-control rounded-3" required placeholder="Nama jalan, nomor gedung/rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Catatan Khusus Pesanan (Opsional)</label>
                        <textarea name="notes" rows="2" class="form-control rounded-3" placeholder="Contoh: Tolong packing kayu / kirim jam kerja..."></textarea>
                    </div>

                    <div class="alert alert-success border-0 rounded-3 small p-3 mb-4 d-flex align-items-start gap-2">
                        <i class="bi bi-whatsapp fs-5 text-success"></i>
                        <div>
                            <strong>Alur Pemesanan Cepat & Aman:</strong>
                            <ol class="mb-0 ps-3 mt-1">
                                <li>Data pesanan Anda tersimpan aman di database dengan Nomor Invoice resmi.</li>
                                <li>Anda akan langsung dialihkan ke WhatsApp resmi sales kami dengan format pesanan lengkap.</li>
                                <li>Admin akan mengonfirmasi ongkos kirim dan nomor rekening pembayaran resmi perusahaan.</li>
                            </ol>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg rounded-pill w-100 fw-bold shadow">
                        <i class="bi bi-whatsapp me-2"></i> Proses Pesanan ke WhatsApp Admin
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-5">
            <div class="card border rounded-4 p-4 shadow-sm bg-light">
                <h5 class="fw-bold mb-3 text-dark">Rincian Item (<?= count($cart) ?>)</h5>

                <div class="d-flex flex-column gap-3 mb-4">
                    <?php foreach ($cart as $item): ?>
                        <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded-3 border">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= UrlHelper::upload($item['image'], 'assets/images/no-image.png') ?>" alt="<?= e($item['name']) ?>" class="rounded" style="width: 45px; height: 45px; object-fit: contain;">
                                <div>
                                    <div class="small fw-semibold text-truncate" style="max-width: 200px;"><?= e($item['name']) ?></div>
                                    <small class="text-muted"><?= (int) $item['quantity'] ?> x <?= Sanitizer::formatRupiah($item['price']) ?></small>
                                </div>
                            </div>
                            <span class="small fw-bold text-danger"><?= Sanitizer::formatRupiah($item['price'] * $item['quantity']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between small text-secondary mb-2">
                        <span>Total Berat</span>
                        <span class="fw-bold text-dark"><?= (int) $totalWeight ?> gram (<?= round($totalWeight/1000, 2) ?> kg)</span>
                    </div>
                    <div class="d-flex justify-content-between small text-secondary mb-3">
                        <span>Subtotal Produk</span>
                        <span class="fw-bold text-dark fs-6"><?= Sanitizer::formatRupiah($subtotal) ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-2">
                        <span class="fw-bold text-dark fs-5">Total Pembayaran</span>
                        <span class="fw-bold text-danger fs-4"><?= Sanitizer::formatRupiah($subtotal) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
