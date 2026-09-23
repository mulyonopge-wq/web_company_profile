<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Tim Manajemen & Pimpinan / Pengurus</h3>
        <p class="text-secondary small mb-0">Kelola daftar jajaran pimpinan, dewan direksi, dan pengurus perusahaan yang tampil di halaman profil (Tentang Kami).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= UrlHelper::base('tentang') ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Lihat di Profil
        </a>
        <a href="<?= UrlHelper::base('admin/teams/tambah') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Anggota / Pimpinan
        </a>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0 table-hover">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th style="width: 70px;" class="text-center">Urutan</th>
                    <th style="width: 90px;" class="text-center">Foto</th>
                    <th>Nama & Gelar</th>
                    <th>Jabatan / Posisi</th>
                    <th>Bio / Keterangan</th>
                    <th style="width: 100px;" class="text-center">Status</th>
                    <th style="width: 130px;" class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($teams)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 text-secondary opacity-50 d-block mb-2"></i>
                            Belum ada anggota tim / pimpinan tersimpan. Klik "Tambah Anggota / Pimpinan" untuk menambahkan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($teams as $t): ?>
                        <tr>
                            <td class="fw-bold text-center text-muted">
                                <span class="badge bg-light text-dark border font-monospace"><?= (int) $t['sort_order'] ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($t['photo'])): ?>
                                    <img src="<?= UrlHelper::upload($t['photo']) ?>" alt="<?= e($t['name']) ?>" class="rounded-circle border shadow-sm object-fit-cover" style="width: 54px; height: 54px;">
                                <?php else: ?>
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle border d-inline-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 54px; height: 54px; font-size: 1.25rem;">
                                        <?= strtoupper(mb_substr($t['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6"><?= e($t['name']) ?></div>
                            </td>
                            <td>
                                <span class="badge text-bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill">
                                    <?= e($t['position']) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-secondary"><?= e(Sanitizer::truncate($t['bio'] ?? '-', 80)) ?></small>
                            </td>
                            <td class="text-center">
                                <?php if ($t['is_active']): ?>
                                    <span class="badge text-bg-success rounded-pill px-2 py-1">Aktif</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary rounded-pill px-2 py-1">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <a href="<?= UrlHelper::base('admin/teams/edit/' . $t['id']) ?>" class="btn btn-sm btn-outline-primary me-1 rounded-pill px-2 py-1" title="Edit Data">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="<?= UrlHelper::base('admin/teams/hapus/' . $t['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus anggota tim ini: <?= addslashes(e($t['name'])) ?>?');">
                                    <?= CsrfHelper::field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
