<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;

$galleryJson = [];
if (!empty($galleries)) {
    foreach ($galleries as $idx => $g) {
        $galleryJson[] = [
            'image' => UrlHelper::upload($g['image']),
            'title' => $g['title'],
            'category' => $g['category'] ?? '',
            'description' => $g['description'] ?? '',
        ];
    }
}
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
                    <div class="card border rounded-4 overflow-hidden shadow-sm h-100 gallery-card-hover" onclick="openGalleryLightbox(<?= $idx ?>)" style="cursor: pointer;">
                        <div class="position-relative overflow-hidden" style="height: 240px; background-color: #f1f5f9;">
                            <?php if (!empty($g['image'])): ?>
                                <img src="<?= $imgUrl ?>" alt="<?= e($g['title']) ?>" class="w-100 h-100 object-fit-cover gallery-img-thumb" loading="lazy">
                            <?php else: ?>
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            <?php endif; ?>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge text-bg-dark bg-opacity-75 rounded-pill"><?= e($g['category']) ?></span>
                            </div>
                            <div class="gallery-hover-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-40 opacity-0 transition-all">
                                <div class="btn btn-light rounded-circle shadow p-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="bi bi-arrows-fullscreen text-primary fs-5"></i>
                                </div>
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
            <?php endforeach; ?>
        </div>

        <!-- Lightbox Modal Tunggal dengan Tombol Geser / Next -->
        <div class="modal fade modal-gallery-lightbox" id="galleryLightboxModal" tabindex="-1" aria-labelledby="galleryLightboxModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-md-down">
                <div class="modal-content bg-dark border-0 shadow-2xl rounded-4 overflow-hidden position-relative" style="background-color: rgba(15, 20, 28, 0.96) !important; backdrop-filter: blur(12px);">
                    <!-- Header Modal -->
                    <div class="modal-header border-0 py-3 px-4 d-flex justify-content-between align-items-center bg-transparent">
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace fs-6">
                                <i class="bi bi-images me-1"></i> <span id="galleryCurrentNum">1</span> / <span id="galleryTotalNum"><?= count($galleryJson) ?></span>
                            </span>
                            <span class="badge bg-secondary bg-opacity-50 text-white rounded-pill px-3 py-2 small" id="galleryCategoryBadge">-</span>
                            <h6 class="modal-title fw-bold text-white text-truncate mb-0" style="max-width: 350px;" id="galleryLightboxTitle">
                                -
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-light btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Tutup" style="width: 38px; height: 38px;" title="Tutup (Esc)">
                                <i class="bi bi-x-lg fs-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Body Modal (Gambar & Tombol Geser Kiri-Kanan) -->
                    <div class="modal-body p-0 d-flex align-items-center justify-content-center position-relative" style="min-height: 420px; max-height: 72vh; overflow: hidden; user-select: none;">
                        <?php if (count($galleryJson) > 1): ?>
                        <!-- Tombol Geser Kiri / Prev -->
                        <button type="button" class="btn-lightbox-nav btn-lightbox-prev position-absolute start-0 top-50 translate-middle-y ms-3 z-3 shadow" onclick="prevGalleryImage()" aria-label="Gambar Sebelumnya" title="Sebelumnya (Panah Kiri)">
                            <i class="bi bi-chevron-left fs-3"></i>
                        </button>
                        <?php endif; ?>

                        <!-- Kontainer Gambar -->
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3 text-center">
                            <img id="galleryLightboxImg" src="" alt="Galeri" class="img-fluid object-fit-contain shadow-sm" style="max-height: 66vh; max-width: 100%; border-radius: 12px; transition: transform 0.25s ease, opacity 0.2s ease;">
                        </div>

                        <?php if (count($galleryJson) > 1): ?>
                        <!-- Tombol Geser Kanan / Next -->
                        <button type="button" class="btn-lightbox-nav btn-lightbox-next position-absolute end-0 top-50 translate-middle-y me-3 z-3 shadow" onclick="nextGalleryImage()" aria-label="Gambar Berikutnya" title="Berikutnya (Panah Kanan)">
                            <i class="bi bi-chevron-right fs-3"></i>
                        </button>
                        <?php endif; ?>
                    </div>

                    <!-- Deskripsi di bagian bawah modal -->
                    <div class="modal-footer border-0 py-3 px-4 justify-content-between align-items-center bg-black bg-opacity-50">
                        <p class="text-white-50 small mb-0 text-truncate" id="galleryLightboxDesc" style="max-width: 80%;"></p>
                        <small class="text-secondary font-monospace"><i class="bi bi-keyboard me-1"></i> Gunakan tombol panah &larr; &rarr;</small>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .gallery-card-hover {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .gallery-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12) !important;
        }
        .gallery-card-hover:hover .gallery-hover-overlay {
            opacity: 1 !important;
        }
        .gallery-card-hover:hover .gallery-img-thumb {
            transform: scale(1.05);
        }
        .gallery-img-thumb {
            transition: transform 0.3s ease;
        }
        .btn-lightbox-nav {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
            cursor: pointer;
            transition: all 0.25s ease-in-out;
        }
        .btn-lightbox-nav:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #ffffff;
            transform: translateY(-50%) scale(1.12);
            box-shadow: 0 0 20px rgba(13, 110, 253, 0.6);
        }
        .btn-lightbox-nav:active {
            transform: translateY(-50%) scale(0.92);
        }
        @media (max-width: 768px) {
            .btn-lightbox-nav {
                width: 42px;
                height: 42px;
            }
            .btn-lightbox-nav i {
                font-size: 1.25rem !important;
            }
        }
        </style>

        <script>
        const galleryItems = <?= json_encode($galleryJson) ?>;
        let currentGalleryIndex = 0;

        function openGalleryLightbox(index) {
            if (index < 0 || index >= galleryItems.length) return;
            setGalleryImage(index);
            const modalEl = document.getElementById('galleryLightboxModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        }

        function setGalleryImage(index) {
            if (!galleryItems || galleryItems.length === 0) return;
            if (index < 0) {
                index = galleryItems.length - 1;
            } else if (index >= galleryItems.length) {
                index = 0;
            }

            currentGalleryIndex = index;
            const item = galleryItems[currentGalleryIndex];

            const imgEl = document.getElementById('galleryLightboxImg');
            if (imgEl) {
                imgEl.style.opacity = '0.4';
                imgEl.src = item.image;
                imgEl.onload = () => {
                    imgEl.style.opacity = '1';
                };
            }

            const titleEl = document.getElementById('galleryLightboxTitle');
            if (titleEl) titleEl.textContent = item.title || '';

            const badgeEl = document.getElementById('galleryCategoryBadge');
            if (badgeEl) {
                if (item.category) {
                    badgeEl.textContent = item.category;
                    badgeEl.style.display = 'inline-block';
                } else {
                    badgeEl.style.display = 'none';
                }
            }

            const descEl = document.getElementById('galleryLightboxDesc');
            if (descEl) descEl.textContent = item.description || '';

            const curNumEl = document.getElementById('galleryCurrentNum');
            if (curNumEl) curNumEl.textContent = (currentGalleryIndex + 1);
        }

        function nextGalleryImage() {
            setGalleryImage(currentGalleryIndex + 1);
        }

        function prevGalleryImage() {
            setGalleryImage(currentGalleryIndex - 1);
        }

        // Navigasi keyboard (Panah Kiri & Panah Kanan)
        document.addEventListener('keydown', (e) => {
            const modalEl = document.getElementById('galleryLightboxModal');
            if (modalEl && modalEl.classList.contains('show')) {
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    prevGalleryImage();
                } else if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    nextGalleryImage();
                }
            }
        });

        // Swipe sentuh di HP/Mobile
        document.addEventListener('DOMContentLoaded', () => {
            const lightboxContainer = document.querySelector('#galleryLightboxModal .modal-body');
            if (lightboxContainer) {
                let touchStartX = 0;
                let touchEndX = 0;

                lightboxContainer.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                lightboxContainer.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchStartX - touchEndX > 45) {
                        nextGalleryImage();
                    } else if (touchEndX - touchStartX > 45) {
                        prevGalleryImage();
                    }
                }, { passive: true });
            }
        });
        </script>
    <?php endif; ?>
</div>
