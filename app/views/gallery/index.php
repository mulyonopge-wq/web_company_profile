<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Galeri Foto</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="text-center mb-5">
        <span class="badge text-bg-primary px-3 py-2 rounded-pill mb-2">Dokumentasi & Portofolio</span>
        <h1 class="fw-bold text-dark">Galeri Fasilitas & Kegiatan Perusahaan</h1>
        <p class="text-secondary mx-auto" style="max-width: 600px;">
            Dokumentasi laboratorium pengujian perangkat, fasilitas gudang logistik, serta implementasi proyek instalasi jaringan bersama klien kami.
        </p>
    </div>

    <?php if (empty($galleries)): ?>
        <div class="card border rounded-4 p-5 text-center my-4">
            <i class="bi bi-images text-secondary display-4 mb-3"></i>
            <h5 class="fw-bold text-dark">Belum Ada Foto Galeri</h5>
            <p class="text-secondary small">Dokumentasi foto akan diperbarui segera.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($galleries as $idx => $g):
                $imgUrl = UrlHelper::upload($g['image']);
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card border rounded-4 overflow-hidden shadow-sm h-100">
                        <div class="position-relative cursor-pointer" style="height: 240px; background-color: #f1f5f9; overflow: hidden;" data-bs-toggle="modal" data-bs-target="#galleryModal-<?= $idx ?>">
                            <?php if (!empty($g['image'])): ?>
                                <img src="<?= $imgUrl ?>" alt="<?= e($g['title']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge text-bg-dark bg-opacity-75 rounded-pill"><?= e($g['category']) ?></span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold mb-1 text-dark"><?= e($g['title']) ?></h6>
                            <?php if (!empty($g['description'])): ?>
                                <p class="text-secondary small mb-0"><?= e($g['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Modal Preview -->
                <div class="modal fade" id="galleryModal-<?= $idx ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 overflow-hidden">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold"><?= e($g['title']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center p-3">
                                <img src="<?= $imgUrl ?>" class="img-fluid rounded-3 mb-3" alt="<?= e($g['title']) ?>">
                                <p class="text-secondary text-start mb-0"><?= e($g['description']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
