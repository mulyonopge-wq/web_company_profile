<div class="py-5 my-5 text-center">
    <div class="container">
        <div class="display-1 fw-bold text-danger mb-3">403</div>
        <h2 class="fw-bold text-dark mb-3">Akses Ditolak (Forbidden)</h2>
        <p class="text-secondary mx-auto mb-4" style="max-width: 500px;">
            Anda tidak memiliki hak akses yang cukup atau sesi keamanan form (CSRF) Anda telah kedaluwarsa.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= \App\Helpers\UrlHelper::base() ?>" class="btn btn-primary px-4 py-2 rounded-pill">
                <i class="bi bi-house-door-fill me-1"></i> Kembali ke Beranda
            </a>
            <a href="<?= \App\Helpers\UrlHelper::base('admin/login') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                <i class="bi bi-lock-fill me-1"></i> Halaman Login
            </a>
        </div>
    </div>
</div>
