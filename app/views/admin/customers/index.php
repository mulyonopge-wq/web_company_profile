<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Daftar Pelanggan</h3>
        <p class="text-secondary small mb-0">Pelanggan yang pernah melakukan transaksi atau pemesanan barang.</p>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Nama Pelanggan</th>
                    <th>Nomor WhatsApp</th>
                    <th>Email</th>
                    <th>Alamat Pengiriman</th>
                    <th>Total Order</th>
                    <th style="width: 120px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada pelanggan terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td class="fw-bold text-dark">
                                <?= e($c['name']) ?>
                            </td>
                            <td>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>" target="_blank" class="text-success text-decoration-none small">
                                    <i class="bi bi-whatsapp me-1"></i> <?= e($c['phone']) ?>
                                </a>
                            </td>
                            <td class="small text-secondary">
                                <?= e($c['email'] ?? '-') ?>
                            </td>
                            <td class="small text-secondary" style="max-width: 300px;">
                                <?= e(Sanitizer::truncate($c['address'], 100)) ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-primary"><?= (int) $c['total_orders'] ?> pesanan</span>
                            </td>
                            <td class="text-end">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $c['phone']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    <i class="bi bi-whatsapp me-1"></i> Hubungi
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
