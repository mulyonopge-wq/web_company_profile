<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Pesanan Masuk</h3>
        <p class="text-secondary small mb-0">Daftar pesanan belanja pelanggan melalui sistem checkout WhatsApp.</p>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="card border rounded-4 p-2 shadow-sm bg-white mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= UrlHelper::base('admin/orders') ?>" class="btn btn-sm rounded-pill <?= empty($currentStatus) ? 'btn-primary' : 'btn-light' ?>">
            Semua
        </a>
        <a href="<?= UrlHelper::base('admin/orders?status=pending') ?>" class="btn btn-sm rounded-pill <?= ($currentStatus === 'pending') ? 'btn-warning text-dark' : 'btn-light' ?>">
            Pending
        </a>
        <a href="<?= UrlHelper::base('admin/orders?status=processing') ?>" class="btn btn-sm rounded-pill <?= ($currentStatus === 'processing') ? 'btn-primary' : 'btn-light' ?>">
            Diproses
        </a>
        <a href="<?= UrlHelper::base('admin/orders?status=shipped') ?>" class="btn btn-sm rounded-pill <?= ($currentStatus === 'shipped') ? 'btn-info text-dark' : 'btn-light' ?>">
            Dikirim
        </a>
        <a href="<?= UrlHelper::base('admin/orders?status=completed') ?>" class="btn btn-sm rounded-pill <?= ($currentStatus === 'completed') ? 'btn-success' : 'btn-light' ?>">
            Selesai
        </a>
        <a href="<?= UrlHelper::base('admin/orders?status=cancelled') ?>" class="btn btn-sm rounded-pill <?= ($currentStatus === 'cancelled') ? 'btn-danger' : 'btn-light' ?>">
            Dibatalkan
        </a>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Nomor Order</th>
                    <th>Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>WhatsApp</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="width: 220px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada pesanan dengan status ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $o):
                        $badgeClass = match($o['status']) {
                            'pending' => 'bg-warning text-dark',
                            'processing' => 'bg-primary text-white',
                            'shipped' => 'bg-info text-dark',
                            'completed' => 'bg-success text-white',
                            'cancelled' => 'bg-danger text-white',
                            default => 'bg-secondary text-white'
                        };
                    ?>
                        <tr>
                            <td>
                                <a href="<?= UrlHelper::base('admin/orders/detail/' . $o['id']) ?>" class="fw-bold text-primary text-decoration-none">
                                    <?= e($o['order_number']) ?>
                                </a>
                            </td>
                            <td class="small text-muted">
                                <?= date('d M Y H:i', strtotime($o['created_at'])) ?>
                            </td>
                            <td class="fw-semibold text-dark">
                                <?= e($o['customer_name']) ?>
                            </td>
                            <td>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $o['customer_phone']) ?>" target="_blank" class="text-success text-decoration-none small">
                                    <i class="bi bi-whatsapp me-1"></i> <?= e($o['customer_phone']) ?>
                                </a>
                            </td>
                            <td class="small text-secondary">
                                <?= (int) $o['item_count'] ?> jenis barang
                            </td>
                            <td class="fw-bold text-dark">
                                <?= Sanitizer::formatRupiah($o['total_amount']) ?>
                            </td>
                            <td>
                                <span class="badge <?= $badgeClass ?> rounded-pill small"><?= strtoupper(e($o['status'])) ?></span>
                            </td>
                            <td class="text-end text-nowrap">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="<?= UrlHelper::base('admin/orders/detail/' . $o['id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1" title="Lihat Detail Pesanan">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>

                                    <?php if ($o['status'] !== 'cancelled'): ?>
                                        <form action="<?= UrlHelper::base('admin/orders/cancel/' . $o['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MEMBATALKAN pesanan <?= e($o['order_number']) ?>?')">
                                            <?= CsrfHelper::field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-2 py-1" title="Batalkan Pesanan">
                                                <i class="bi bi-x-circle me-1"></i>Batal
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form action="<?= UrlHelper::base('admin/orders/delete/' . $o['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN pesanan <?= e($o['order_number']) ?>?\n\nData pesanan dan daftar produk terkait akan dihapus secara permanen.')">
                                        <?= CsrfHelper::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Hapus Pesanan">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
