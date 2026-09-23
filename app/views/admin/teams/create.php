<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Tambah Anggota Tim / Pimpinan</h3>
        <p class="text-secondary small mb-0">Tambahkan profil direksi, manajemen, atau dewan pengurus baru.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/teams') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="<?= UrlHelper::base('admin/teams/simpan') ?>" method="POST" enctype="multipart/form-data">
            <?= CsrfHelper::field() ?>

            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3">Informasi Anggota & Posisi</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Lengkap & Gelar *</label>
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Contoh: Ir. Hendra Gunawan, S.T., M.T." required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Jabatan / Posisi *</label>
                    <input type="text" name="position" class="form-control" placeholder="Contoh: Direktur Utama, Komisaris, General Manager" required>
                    <small class="text-muted">Akan ditampilkan sebagai titel di bawah nama anggota.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Foto Anggota / Pimpinan</label>
                    <input type="file" name="photo" class="form-control" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Format yang didukung: JPG, PNG, WEBP. Disarankan foto portrait atau rasio 1:1 persegi (Maks 2MB).</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Bio / Keterangan Singkat</label>
                    <textarea name="bio" rows="4" class="form-control" placeholder="Pengalaman singkat, latar belakang keahlian, atau tugas kepemimpinan..."></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Urutan Tampil (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        <small class="text-muted">Angka lebih kecil tampil lebih awal (misal: 1 untuk Direktur Utama / Komisaris).</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" checked>
                            <label class="form-check-label fw-semibold" for="isActiveCheck">
                                Tampilkan di Halaman Profil (Status Aktif)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?= UrlHelper::base('admin/teams') ?>" class="btn btn-light border rounded-pill px-4">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                    <i class="bi bi-floppy-fill me-1"></i> Simpan Data Tim
                </button>
            </div>
        </form>
    </div>
</div>
