<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="bi bi-github text-primary me-2"></i>Pembaruan dari GitHub</h4>
        <p class="text-secondary small mb-0">Kelola dan perbarui source code website langsung dari repositori GitHub secara instan.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= UrlHelper::base('admin/update') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Halaman
        </a>
    </div>
</div>

<?php if (!$execAllowed): ?>
<div class="alert alert-warning border-0 shadow-sm d-flex gap-3 align-items-start mb-4">
    <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
    <div>
        <h6 class="fw-bold mb-1">Fungsi Eksekusi Perintah Server Dinonaktifkan</h6>
        <p class="small mb-2">Fungsi <code>exec()</code> atau <code>shell_exec()</code> dinonaktifkan pada konfigurasi <code>php.ini</code> server aaPanel Anda demi keamanan default.</p>
        <div class="p-3 bg-light rounded-3 small">
            <strong>Cara Mengaktifkan di aaPanel:</strong>
            <ol class="mb-0 ps-3 mt-1">
                <li>Buka <strong>aaPanel</strong> > masuk menu <strong>App Store</strong>.</li>
                <li>Cari versi <strong>PHP</strong> yang Anda gunakan > klik <strong>Settings</strong>.</li>
                <li>Pilih tab <strong>Disabled functions</strong>.</li>
                <li>Cari dan hapus <code>exec</code> dan <code>shell_exec</code> dari daftar, lalu klik Simpan / Restart PHP.</li>
            </ol>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Repository Info Card -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="text-secondary small fw-semibold text-uppercase mb-1">Versi Rilis / Tag</div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary fs-6 px-3 py-2"><i class="bi bi-tag-fill me-1"></i><?= e($gitInfo['current_tag'] ?: 'v1.0.0') ?></span>
            </div>
            <small class="text-muted mt-2">Versi aplikasi saat ini</small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="text-secondary small fw-semibold text-uppercase mb-1">Branch Aktif</div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark fs-6 px-3 py-2"><i class="bi bi-git me-1"></i><?= e($gitInfo['branch'] ?: 'main') ?></span>
            </div>
            <small class="text-muted mt-2">Cabang pelacakan git</small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 p-3">
            <div class="text-secondary small fw-semibold text-uppercase mb-1">Commit Terakhir Terpasang</div>
            <div class="fw-bold text-dark font-monospace text-truncate" title="<?= e($gitInfo['current_commit']) ?>">
                <?= e($gitInfo['current_commit'] ?: 'Belum terdeteksi') ?>
            </div>
            <small class="text-secondary mt-1 text-truncate" title="<?= e($gitInfo['remote_url']) ?>">
                <i class="bi bi-link-45deg me-1"></i>Remote: <?= e($gitInfo['remote_url'] ?: '-') ?>
            </small>
        </div>
    </div>
</div>

<!-- Actions Card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-play-circle-fill text-primary me-2"></i>Aksi Pembaruan</h6>
    </div>
    <div class="card-body p-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <h6 class="fw-bold mb-1">Sinkronisasi Otomatis dengan GitHub</h6>
                <p class="text-secondary small mb-0">
                    Sistem akan menarik (*pull*) pembaruan kode terbaru dari repositori GitHub resmi.
                    File konfigurasi <code>.env</code> dan folder unggahan gambar/video <code>public/uploads/</code> <strong>tidak akan terhapus</strong>.
                </p>
                <?php if (!empty($gitInfo['has_uncommitted'])): ?>
                <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i> Terdapat perubahan lokal di server yang belum di-commit.
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-5 text-lg-end d-flex flex-wrap justify-content-lg-end gap-2">
                <!-- Check Button -->
                <form action="<?= UrlHelper::base('admin/update/check') ?>" method="POST" class="d-inline">
                    <?= CsrfHelper::field() ?>
                    <button type="submit" class="btn btn-outline-primary" <?= !$execAllowed ? 'disabled' : '' ?>>
                        <i class="bi bi-cloud-arrow-down me-1"></i> Cek Pembaruan
                    </button>
                </form>

                <!-- Pull Button -->
                <form action="<?= UrlHelper::base('admin/update/pull') ?>" method="POST" class="d-inline">
                    <?= CsrfHelper::field() ?>
                    <button type="submit" class="btn btn-primary" <?= !$execAllowed ? 'disabled' : '' ?> onclick="return confirm('Apakah Anda yakin ingin menarik pembaruan dari GitHub sekarang?')">
                        <i class="bi bi-download me-1"></i> Tarik Pembaruan
                    </button>
                </form>

                <!-- Force Reset Button -->
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#resetHardModal" <?= !$execAllowed ? 'disabled' : '' ?>>
                    <i class="bi bi-arrow-repeat me-1"></i> Reset Paksa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Output Terminal Log -->
<?php if (!empty($outputLog)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-dark text-white py-2 d-flex justify-content-between align-items-center">
        <span class="small font-monospace"><i class="bi bi-terminal-fill me-2 text-warning"></i>Terminal Output Log</span>
        <span class="badge bg-<?= e($outputLog['type'] ?? 'secondary') ?>"><?= e($outputLog['command'] ?? 'git') ?></span>
    </div>
    <div class="card-body bg-black text-light p-3 font-monospace" style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; max-height: 350px; overflow-y: auto;">
        <div class="text-warning small mb-1">$ <?= e($outputLog['command'] ?? '') ?></div>
        <div class="text-<?= ($outputLog['type'] ?? '') === 'danger' ? 'danger' : 'info' ?> small fw-bold mb-2">
            <?= e($outputLog['title'] ?? '') ?>
        </div>
        <pre class="mb-0 text-white-50" style="font-size: 0.82rem; white-space: pre-wrap;"><?= e($outputLog['output'] ?? '') ?></pre>
    </div>
</div>
<?php endif; ?>

<!-- Alternative: Manual CLI Command Guide -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="fw-bold mb-0"><i class="bi bi-terminal me-2 text-secondary"></i>Alternatif Melalui Terminal Server (CLI)</h6>
    </div>
    <div class="card-body p-4">
        <p class="text-secondary small mb-2">Jika Anda lebih menyukai menggunakan Terminal aaPanel / SSH secara langsung, jalankan perintah berikut:</p>
        <div class="bg-light p-3 rounded-3 position-relative font-monospace small">
            <code>cd <?= e($repoPath ?? '/www/wwwroot/gambiran.bumdes13.id') ?><br>git pull origin main</code>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Reset Hard -->
<div class="modal fade" id="resetHardModal" tabindex="-1" aria-labelledby="resetHardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom bg-danger text-white">
                <h5 class="modal-title fs-6 fw-bold" id="resetHardModalLabel">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i>Konfirmasi Reset Paksa ke Versi GitHub
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-secondary mb-2">
                    Fitur ini akan menjalankan:
                </p>
                <div class="bg-light p-2 rounded font-monospace small mb-3">
                    <code>git fetch origin main && git reset --hard origin/main</code>
                </div>
                <div class="alert alert-warning small py-2 mb-3">
                    <i class="bi bi-shield-check me-1 text-success"></i> <strong>Aman:</strong> File <code>.env</code> dan file foto/video yang diupload di <code>public/uploads/</code> <strong>tidak akan terhapus</strong>.
                </div>
                <p class="small text-danger mb-0">
                    Opsi ini sangat berguna jika terjadi error konflik file saat git pull normal. Seluruh file kode program akan diselaraskan persis seperti di repositori GitHub.
                </p>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <form action="<?= UrlHelper::base('admin/update/reset') ?>" method="POST" class="d-inline">
                    <?= CsrfHelper::field() ?>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-check-circle me-1"></i> Ya, Reset Paksa Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
