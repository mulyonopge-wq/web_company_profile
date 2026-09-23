<div class="py-5 my-5 text-center">
    <div class="container">
        <div class="display-1 fw-bold text-primary mb-3">404</div>
        <h2 class="fw-bold text-dark mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-secondary mx-auto mb-4" style="max-width: 500px;">
            Mohon maaf, halaman atau produk yang Anda cari tidak tersedia, telah dihapus, atau tautan yang Anda tuju salah.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= \App\Helpers\UrlHelper::base() ?>" class="btn btn-primary px-4 py-2 rounded-pill">
                <i class="bi bi-house-door-fill me-1"></i> Kembali ke Beranda
            </a>
            <a href="<?= \App\Helpers\UrlHelper::base('produk') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                <i class="bi bi-grid me-1"></i> Lihat Katalog Produk
            </a>
        </div>
    </div>
</div>
