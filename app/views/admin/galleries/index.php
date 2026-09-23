<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Kelola Galeri Foto</h3>
        <p class="text-secondary small mb-0">Foto dokumentasi proyek, lab, dan fasilitas perusahaan.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/galleries/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Unggah Foto Baru
        </a>
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
