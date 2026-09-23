<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Edit Produk</h3>
        <p class="text-secondary small mb-0">Perbarui informasi: <?= e($product['name']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= UrlHelper::base('produk/' . $product['slug']) ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Lihat Halaman
        </a>
        <a href="<?= UrlHelper::base('admin/products') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<form action="<?= UrlHelper::base('admin/products/update/' . $product['id']) ?>" method="POST" enctype="multipart/form-data">
    <?= CsrfHelper::field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Informasi Utama Produk</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Produk *</label>
                    <input type="text" name="name" class="form-control" data-slug-source value="<?= e($product['name']) ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">SKU / Kode Barang *</label>
                        <input type="text" name="sku" class="form-control" value="<?= e($product['sku']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Slug URL (SEO)</label>
                        <input type="text" name="slug" class="form-control" data-slug-target value="<?= e($product['slug']) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Singkat</label>
                    <textarea name="short_description" rows="3" class="form-control"><?= e($product['short_description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Lengkap</label>
                    <textarea name="full_description" rows="6" class="form-control"><?= e($product['full_description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Spesifikasi Teknis (Judul: Nilai per baris)</label>
                    <textarea name="specification" rows="6" class="form-control font-monospace small"><?= e($product['specification']) ?></textarea>
                </div>
            </div>

            <!-- Photos & Gallery Upload -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Foto Produk</h5>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Foto Utama Produk</label>
                    <?php if (!empty($product['main_image'])): ?>
                        <div class="mb-2 p-2 bg-light border rounded text-center" style="max-width: 200px;">
                            <img src="<?= UrlHelper::upload($product['main_image']) ?>" alt="Main Image" class="img-fluid" style="max-height: 140px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="main_image" class="form-control" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto utama.</small>
                </div>

                <!-- Existing Gallery Photos -->
                <?php if (!empty($galleryImages)): ?>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold d-block">Foto Tambahan Terpasang (<?= count($galleryImages) ?>)</label>
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach ($galleryImages as $g): ?>
                                <div class="position-relative border rounded p-1" style="width: 100px; height: 100px;">
                                    <img src="<?= UrlHelper::upload($g['image_path']) ?>" class="w-100 h-100 object-fit-contain">
                                    <button type="submit" form="del-img-<?= $g['id'] ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0 rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;" title="Hapus foto ini" onclick="return confirm('Hapus foto ini?');">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tambah Foto Galeri Baru</label>
                    <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/png, image/jpeg, image/webp">
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-play-circle-fill text-danger me-2"></i>Media Video Produk (Opsional)</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-youtube text-danger me-1"></i> Tautan / Link Video YouTube</label>
                    <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." value="<?= e($product['video_url'] ?? '') ?>">
                    <small class="text-muted">Masukkan link video YouTube produk untuk ditayangkan di halaman produk.</small>
                    <?php if (!empty($product['video_url'])): ?>
                        <div class="mt-2">
                            <a href="<?= e($product['video_url']) ?>" target="_blank" class="small text-danger text-decoration-none">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Buka Tautan Video Saat Ini
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-file-earmark-play text-primary me-1"></i> Upload File Video (MP4 / WebM)</label>
                    <?php if (!empty($product['video_file'])): ?>
                        <div class="mb-2 p-2 bg-light border rounded d-flex align-items-center justify-content-between">
                            <span class="small text-truncate" style="max-width: 250px;"><i class="bi bi-camera-video me-1"></i> <?= e(basename($product['video_file'])) ?></span>
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="checkbox" name="remove_video_file" value="1" id="rmVid">
                                <label class="form-check-label small text-danger" for="rmVid">Hapus File Video</label>
                            </div>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="video_file" class="form-control" accept="video/mp4, video/webm, video/ogg">
                    <small class="text-muted">Format video: MP4, WebM (Maks 50MB). Biarkan kosong jika tidak ingin mengganti.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Classification & Status -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Kategori & Status</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Kategori Produk *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveProd" <?= $product['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="isActiveProd">Publikasikan Produk (Aktif)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedProd" <?= $product['is_featured'] ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="isFeaturedProd">Jadikan Produk Unggulan</label>
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Harga & Stok</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Harga Normal (Rp) *</label>
                    <input type="number" name="price" class="form-control" value="<?= (int) $product['price'] ?>" required min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Harga Diskon / Promo (Rp)</label>
                    <input type="number" name="discount_price" class="form-control" value="<?= !empty($product['discount_price']) ? (int) $product['discount_price'] : '' ?>" min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Stok Unit *</label>
                    <input type="number" name="stock" class="form-control" value="<?= (int) $product['stock'] ?>" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Berat Produk (Gram) *</label>
                    <input type="number" name="weight" class="form-control" value="<?= (int) $product['weight'] ?>" min="1" required>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                    <i class="bi bi-floppy-fill me-2"></i> Simpan Perubahan Produk
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Separate hidden delete forms for gallery images -->
<?php if (!empty($galleryImages)): ?>
    <?php foreach ($galleryImages as $g): ?>
        <form id="del-img-<?= $g['id'] ?>" action="<?= UrlHelper::base('admin/products/delete-image/' . $g['id']) ?>" method="POST" style="display:none;">
            <?= CsrfHelper::field() ?>
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        </form>
    <?php endforeach; ?>
<?php endif; ?>
