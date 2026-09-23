<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Anggota Tim / Pimpinan</h3>
        <p class="text-secondary small mb-0">Perbarui data profil, foto, atau posisi jabatan.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/teams') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="<?= UrlHelper::base('admin/teams/update/' . $team['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= CsrfHelper::field() ?>

            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3">Informasi Anggota & Posisi</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Lengkap & Gelar *</label>
                    <input type="text" name="name" class="form-control form-control-lg" value="<?= e($team['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Jabatan / Posisi *</label>
                    <input type="text" name="position" class="form-control" value="<?= e($team['position']) ?>" required>
                    <small class="text-muted">Akan ditampilkan sebagai titel di bawah nama anggota.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Foto Saat Ini</label>
                    <div class="d-flex align-items-center gap-3 mb-2 p-3 bg-light rounded-3 border">
                        <?php if (!empty($team['photo'])): ?>
                            <img src="<?= UrlHelper::upload($team['photo']) ?>" alt="<?= e($team['name']) ?>" class="rounded-circle border shadow-sm object-fit-cover" style="width: 70px; height: 70px;">
                            <div>
                                <div class="small fw-semibold text-success"><i class="bi bi-check-circle-fill me-1"></i> Foto sudah terunggah</div>
                                <small class="text-muted">Pilih file baru di bawah jika ingin mengganti foto saat ini.</small>
                            </div>
                        <?php else: ?>
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle border d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 70px; height: 70px; font-size: 1.5rem;">
                                <?= strtoupper(mb_substr($team['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="small fw-semibold text-secondary">Belum ada foto yang diunggah (menggunakan avatar inisial)</div>
                                <small class="text-muted">Pilih file foto di bawah untuk memasang foto anggota.</small>
                            </div>
                        <?php endif; ?>
                    </div>
                    <label class="form-label small fw-semibold">Unggah Foto Baru (Opsional)</label>
                    <input type="file" name="photo" class="form-control" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Format yang didukung: JPG, PNG, WEBP. Disarankan rasio 1:1 persegi atau foto portrait resmi (Maks 2MB).</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Bio / Keterangan Singkat</label>
                    <textarea name="bio" rows="4" class="form-control"><?= e($team['bio'] ?? '') ?></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Urutan Tampil (Sort Order)</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= (int) $team['sort_order'] ?>" min="0">
                        <small class="text-muted">Angka lebih kecil tampil lebih awal.</small>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActiveCheck" value="1" <?= $team['is_active'] ? 'checked' : '' ?>>
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
                    <i class="bi bi-floppy-fill me-1"></i> Simpan Pembaruan
                </button>
            </div>
        </form>
    </div>
</div>
