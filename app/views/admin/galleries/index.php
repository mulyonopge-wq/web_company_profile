<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Galeri Foto</h3>
        <p class="text-secondary small mb-0">Foto dokumentasi proyek, lab, dan fasilitas perusahaan.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-primary btn-sm rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#editGalleryTextsCollapse" aria-expanded="false" aria-controls="editGalleryTextsCollapse">
            <i class="bi bi-pencil-square me-1"></i> Edit Judul & Keterangan
        </button>
        <a href="<?= UrlHelper::base('galeri') ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Lihat Halaman Galeri
        </a>
        <a href="<?= UrlHelper::base('admin/galleries/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Unggah Foto Baru
        </a>
    </div>
</div>

<!-- Form Edit Kata-kata Judul & Keterangan Halaman Galeri -->
<div class="collapse mb-4" id="editGalleryTextsCollapse">
    <div class="card border rounded-4 p-4 shadow-sm bg-white">
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-fonts fs-6"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Ubah Kata-kata Judul & Keterangan Galeri</h5>
                <small class="text-secondary">Kata-kata ini akan tampil di bagian pembuka / header halaman Galeri (<code>/galeri</code>).</small>
            </div>
        </div>

        <form action="<?= UrlHelper::base('admin/galleries/settings') ?>" method="POST">
            <?= CsrfHelper::field() ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Label / Badge Seksi</label>
                    <input type="text" name="gallery_page_badge" class="form-control" value="<?= e($galleryBadge ?? 'Dokumentasi & Portofolio') ?>" placeholder="Contoh: Dokumentasi & Portofolio">
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Judul Halaman Galeri *</label>
                    <input type="text" name="gallery_page_title" class="form-control" value="<?= e($galleryTitle ?? 'Galeri Fasilitas & Kegiatan Perusahaan') ?>" placeholder="Contoh: Galeri Fasilitas & Kegiatan Perusahaan" required>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold">Kata-kata Keterangan / Deskripsi Sub-judul</label>
                    <textarea name="gallery_page_subtitle" rows="3" class="form-control" placeholder="Tuliskan kata-kata pengantar / ulasan di bawah judul..."><?= e($gallerySubtitle ?? 'Dokumentasi laboratorium pengujian perangkat, fasilitas gudang logistik, serta implementasi proyek instalasi jaringan bersama klien kami.') ?></textarea>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light btn-sm border rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#editGalleryTextsCollapse">Tutup</button>
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan Teks
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3">
    <?php if (empty($galleries)): ?>
        <div class="col-12">
            <div class="card border rounded-4 p-5 text-center bg-white shadow-sm">
                <i class="bi bi-camera text-secondary display-4 mb-2"></i>
                <h5 class="fw-bold">Belum Ada Foto</h5>
                <p class="text-muted small">Silakan unggah foto pertama Anda.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($galleries as $g): ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card border rounded-4 overflow-hidden shadow-sm bg-white h-100">
                    <div style="height: 160px; background: #e2e8f0;">
                        <?php if (!empty($g['image'])): ?>
                            <img src="<?= UrlHelper::upload($g['image']) ?>" alt="<?= e($g['title']) ?>" class="w-100 h-100 object-fit-cover">
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-3">
                        <span class="badge text-bg-light border text-primary mb-1 small"><?= e($g['category']) ?></span>
                        <h6 class="fw-bold text-dark mb-1 text-truncate"><?= e($g['title']) ?></h6>
                        <?php if (!empty($g['description'])): ?>
                            <small class="text-secondary d-block text-truncate mb-2"><?= e($g['description']) ?></small>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
                            <span class="small text-muted">Urutan: <?= (int)$g['sort_order'] ?></span>
                            <form action="<?= UrlHelper::base('admin/galleries/delete/' . $g['id']) ?>" method="POST" class="d-inline">
                                <?= CsrfHelper::field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger p-1 px-2 rounded-circle" data-confirm-delete="Hapus foto ini dari galeri?" title="Hapus">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
