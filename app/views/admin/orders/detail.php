<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;

$badgeClass = match($order['status']) {
    'pending' => 'bg-warning text-dark',
    'processing' => 'bg-primary text-white',
    'shipped' => 'bg-info text-dark',
    'completed' => 'bg-success text-white',
    'cancelled' => 'bg-danger text-white',
    default => 'bg-secondary text-white'
};
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Detail Pesanan: <?= e($order['order_number']) ?></h3>
        <p class="text-secondary small mb-0">Dibuat pada: <?= date('d F Y, H:i WIB', strtotime($order['created_at'])) ?></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php if ($order['status'] !== 'cancelled'): ?>
            <form action="<?= UrlHelper::base('admin/orders/cancel/' . $order['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan <?= e($order['order_number']) ?>?')">
                <?= CsrfHelper::field() ?>
                <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                    <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan
                </button>
            </form>
        <?php endif; ?>
        <form action="<?= UrlHelper::base('admin/orders/delete/' . $order['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN pesanan <?= e($order['order_number']) ?>?\n\nData pesanan dan daftar produk terkait akan dihapus secara permanen.')">
            <?= CsrfHelper::field() ?>
            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                <i class="bi bi-trash me-1"></i> Hapus Pesanan
            </button>
        </form>
        <a href="<?= UrlHelper::base('admin/orders') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Order Items List -->
    <div class="col-lg-8">
        <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
            <h5 class="fw-bold mb-3 text-dark">Daftar Produk yang Dipesan</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light small">
                        <tr>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($item['main_image'])): ?>
                                            <img src="<?= UrlHelper::upload($item['main_image']) ?>" class="rounded border" style="width: 45px; height: 45px; object-fit: contain;">
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-semibold text-dark small"><?= e($item['product_name']) ?></div>
                                            <?php if (!empty($item['product_slug'])): ?>
                                                <a href="<?= UrlHelper::base('produk/' . $item['product_slug']) ?>" target="_blank" class="small text-muted text-decoration-none">
                                                    Lihat halaman produk <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="small"><?= Sanitizer::formatRupiah($item['price']) ?></td>
                                <td class="text-center fw-bold small"><?= (int) $item['quantity'] ?></td>
                                <td class="text-end fw-bold text-dark small"><?= Sanitizer::formatRupiah($item['subtotal']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end fs-6">Total Belanja:</th>
                            <th class="text-end text-danger fs-5 fw-bold"><?= Sanitizer::formatRupiah($order['total_amount']) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Special Notes -->
        <?php if (!empty($order['notes'])): ?>
            <div class="card border rounded-4 p-4 shadow-sm bg-white">
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-chat-left-text me-2 text-primary"></i>Catatan Pembeli:</h6>
                <p class="text-secondary mb-0 bg-light p-3 rounded-3 small">
                    <?= nl2br(e($order['notes'])) ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Customer Details & Status Control -->
    <div class="col-lg-4">
        <!-- Status Control -->
        <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
            <h5 class="fw-bold mb-3 text-dark">Status Pesanan</h5>
            <div class="mb-3">
                <span class="badge <?= $badgeClass ?> px-3 py-2 fs-6 rounded-pill w-100 text-center">
                    <?= strtoupper(e($order['status'])) ?>
                </span>
            </div>

            <form action="<?= UrlHelper::base('admin/orders/status/' . $order['id']) ?>" method="POST">
                <?= CsrfHelper::field() ?>
                <label class="form-label small fw-semibold">Ubah Status Menjadi:</label>
                <select name="status" class="form-select form-select-sm mb-3">
                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending (Menunggu)</option>
                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing (Diproses)</option>
                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped (Dikirim)</option>
                    <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed (Selesai)</option>
                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled (Dibatalkan)</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">
                    <i class="bi bi-check2-circle me-1"></i> Perbarui Status
                </button>
            </form>

            <hr class="my-3">

            <div class="d-flex flex-column gap-2">
                <?php if ($order['status'] !== 'cancelled'): ?>
                    <form action="<?= UrlHelper::base('admin/orders/cancel/' . $order['id']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan <?= e($order['order_number']) ?>?')">
                        <?= CsrfHelper::field() ?>
                        <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill w-100">
                            <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan Ini
                        </button>
                    </form>
                <?php endif; ?>

                <form action="<?= UrlHelper::base('admin/orders/delete/' . $order['id']) ?>" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN pesanan <?= e($order['order_number']) ?>?\n\nData pesanan dan daftar produk terkait akan dihapus secara permanen.')">
                    <?= CsrfHelper::field() ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                        <i class="bi bi-trash me-1"></i> Hapus Pesanan Permanen
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Card -->
        <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
            <h5 class="fw-bold mb-3 text-dark">Data Pelanggan</h5>

            <div class="mb-3">
                <small class="text-muted text-uppercase fw-semibold d-block">Nama Lengkap</small>
                <div class="fw-bold text-dark"><?= e($order['customer_name']) ?></div>
            </div>

            <div class="mb-3">
                <small class="text-muted text-uppercase fw-semibold d-block">WhatsApp</small>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $order['customer_phone']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 mt-1">
                    <i class="bi bi-whatsapp me-1"></i> <?= e($order['customer_phone']) ?>
                </a>
            </div>

            <?php if (!empty($order['customer_email'])): ?>
                <div class="mb-3">
                    <small class="text-muted text-uppercase fw-semibold d-block">Email</small>
                    <div class="text-secondary small"><?= e($order['customer_email']) ?></div>
                </div>
            <?php endif; ?>

            <div class="mb-0">
                <small class="text-muted text-uppercase fw-semibold d-block">Alamat Pengiriman</small>
                <div class="text-secondary small bg-light p-2 rounded-3 mt-1">
                    <?= nl2br(e($order['customer_address'])) ?>
                </div>
            </div>
        </div>
    </div>
</div>
