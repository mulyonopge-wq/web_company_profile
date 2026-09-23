<div class="py-5 my-5 text-center">
    <div class="container">
        <div class="display-1 fw-bold text-warning mb-3">500</div>
        <h2 class="fw-bold text-dark mb-3">Terjadi Kendala Sistem</h2>
        <p class="text-secondary mx-auto mb-4" style="max-width: 500px;">
            Sistem kami sedang mengalami kendala teknis sementara. Tim kami telah mencatat log kesalahan ini dan sedang menanganinya.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= \App\Helpers\UrlHelper::base() ?>" class="btn btn-primary px-4 py-2 rounded-pill">
                <i class="bi bi-house-door-fill me-1"></i> Kembali ke Beranda
            </a>
            <a href="<?= \App\Helpers\UrlHelper::base('kontak') ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                <i class="bi bi-chat-dots-fill me-1"></i> Hubungi Dukungan
            </a>
        </div>
    </div>
</div>
