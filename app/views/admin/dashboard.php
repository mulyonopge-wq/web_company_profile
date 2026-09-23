<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Dashboard</h3>
        <p class="text-secondary small mb-0">Ringkasan aktivitas toko dan performa website Anda.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/products/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
        </a>
    </div>
</div>

<!-- Metrics Overview -->
<div class="row g-3 mb-4">
    <!-- Total Products -->
    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div>
                <div class="metric-title">Total Produk</div>
                <h3 class="metric-number"><?= (int) $stats['total_products'] ?></h3>
                <small class="text-success"><i class="bi bi-check2"></i> <?= (int) $stats['active_products'] ?> aktif</small>
            </div>
            <div class="metric-icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div>
                <div class="metric-title">Total Pesanan</div>
                <h3 class="metric-number"><?= (int) $stats['total_orders'] ?></h3>
                <small class="text-warning"><i class="bi bi-clock"></i> <?= (int) $stats['pending_orders'] ?> pending</small>
            </div>
            <div class="metric-icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-cart-check"></i>
            </div>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div>
                <div class="metric-title">Pelanggan</div>
                <h3 class="metric-number"><?= (int) $stats['total_customers'] ?></h3>
                <small class="text-muted">Kontak tersimpan</small>
            </div>
            <div class="metric-icon bg-info bg-opacity-10 text-info">
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-xl-3 col-sm-6">
        <div class="metric-card">
            <div>
                <div class="metric-title">Estimasi Penjualan</div>
                <h3 class="metric-number fs-4"><?= Sanitizer::formatRupiah($stats['total_revenue']) ?></h3>
                <small class="text-success"><i class="bi bi-arrow-up-short"></i> Dari pesanan diproses</small>
            </div>
            <div class="metric-icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border rounded-4 p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Stok Habis / Kritis</small>
                    <h4 class="fw-bold text-danger mb-0"><?= (int) $stats['out_of_stock'] ?> Produk</h4>
                </div>
                <i class="bi bi-exclamation-triangle-fill text-danger fs-3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border rounded-4 p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Pesanan Diproses</small>
                    <h4 class="fw-bold text-primary mb-0"><?= (int) $stats['processing_orders'] ?> Pesanan</h4>
                </div>
                <i class="bi bi-arrow-repeat text-primary fs-3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border rounded-4 p-3 bg-white shadow-sm">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Total Artikel</small>
                    <h4 class="fw-bold text-dark mb-0"><?= (int) $stats['total_articles'] ?> Post</h4>
                </div>
                <i class="bi bi-journal-richtext text-secondary fs-3"></i>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Recent Orders -->
<div class="row g-4">
    <!-- Chart.js Graph -->
    <div class="col-lg-7">
        <div class="card border rounded-4 p-4 shadow-sm bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Tren Penjualan (6 Bulan Terakhir)</h5>
                <span class="badge text-bg-light border text-secondary">Statistik Bulanan</span>
            </div>
            <div style="position: relative; height: 280px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="col-lg-5">
        <div class="card border rounded-4 p-4 shadow-sm bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Pesanan Terbaru</h5>
                <a href="<?= UrlHelper::base('admin/orders') ?>" class="small text-decoration-none">Semua &rarr;</a>
            </div>

            <?php if (empty($stats['recent_orders'])): ?>
                <p class="text-muted small text-center my-4">Belum ada pesanan masuk.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-2">
                    <?php foreach ($stats['recent_orders'] as $ord):
                        $badgeClass = match($ord['status']) {
                            'pending' => 'bg-warning text-dark',
                            'processing' => 'bg-primary text-white',
                            'shipped' => 'bg-info text-dark',
                            'completed' => 'bg-success text-white',
                            'cancelled' => 'bg-danger text-white',
                            default => 'bg-secondary text-white'
                        };
                    ?>
                        <div class="p-2 border rounded-3 d-flex justify-content-between align-items-center">
                            <div>
                                <a href="<?= UrlHelper::base('admin/orders/detail/' . $ord['id']) ?>" class="fw-bold text-dark text-decoration-none small d-block">
                                    <?= e($ord['order_number']) ?>
                                </a>
                                <small class="text-muted"><?= e($ord['customer_name']) ?> &bull; <?= Sanitizer::formatRupiah($ord['total_amount']) ?></small>
                            </div>
                            <span class="badge <?= $badgeClass ?> rounded-pill small"><?= e(strtoupper($ord['status'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        const chartData = <?= json_encode($stats['monthly_chart'] ?? []) ?>;
        const labels = chartData.map(item => item.label);
        const data = chartData.map(item => parseFloat(item.total) || 0);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['Bulan Lalu', 'Bulan Ini'],
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: data.length ? data : [0, 0],
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
