<?php
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Tambah Produk Baru</h3>
        <p class="text-secondary small mb-0">Isi formulir data produk untuk ditampilkan di marketplace.</p>
    </div>
    <a href="<?= UrlHelper::base('admin/products') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<form action="<?= UrlHelper::base('admin/products/store') ?>" method="POST" enctype="multipart/form-data">
    <?= CsrfHelper::field() ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Informasi Utama Produk</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Produk *</label>
                    <input type="text" name="name" class="form-control" data-slug-source required placeholder="Contoh: MikroTik RouterBOARD RB750Gr3 (hEX)">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">SKU / Kode Barang *</label>
                        <input type="text" name="sku" class="form-control" required placeholder="Contoh: NET-MTK-001">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Slug URL (SEO)</label>
                        <input type="text" name="slug" class="form-control" data-slug-target placeholder="mikrotik-rb750gr3">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Singkat (Ringkasan Cepat)</label>
                    <textarea name="short_description" rows="3" class="form-control" placeholder="1-2 kalimat ringkasan fitur utama..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Lengkap</label>
                    <textarea name="full_description" rows="6" class="form-control" placeholder="Penjelasan lengkap kegunaan, kelebihan, dan informasi produk..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Spesifikasi Teknis (Gunakan format Judul: Nilai per baris)</label>
                    <textarea name="specification" rows="6" class="form-control font-monospace small" placeholder="CPU: Dual Core MT7621A 880 MHz&#10;RAM: 256 MB&#10;Port Ethernet: 5 x Gigabit 10/100/1000&#10;OS: RouterOS Level 4"></textarea>
                    <small class="text-muted">Setiap baris dengan tanda titik dua (:) akan otomatis diubah menjadi tabel spesifikasi.</small>
                </div>
            </div>

            <!-- Photos & Gallery Upload -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Foto & Galeri Produk</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Foto Utama Produk *</label>
                    <input type="file" name="main_image" class="form-control" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Rekomendasi rasio 1:1 (persegi), resolusi min. 600x600px.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Foto Tambahan / Galeri (Bisa pilih beberapa file)</label>
                    <input type="file" name="gallery_images[]" class="form-control" multiple accept="image/png, image/jpeg, image/webp">
                </div>

                <hr class="my-4">
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-play-circle-fill text-danger me-2"></i>Media Video Produk (Opsional)</h6>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-youtube text-danger me-1"></i> Tautan / Link Video YouTube</label>
                    <input type="url" name="video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/...">
                    <small class="text-muted">Masukkan link video YouTube produk untuk ditayangkan di halaman produk.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-file-earmark-play text-primary me-1"></i> Upload File Video (MP4 / WebM)</label>
                    <input type="file" name="video_file" class="form-control" accept="video/mp4, video/webm, video/ogg">
                    <small class="text-muted">Format video: MP4, WebM (Maks 50MB).</small>
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
                            <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActiveProd" checked>
                        <label class="form-check-label small fw-semibold" for="isActiveProd">Publikasikan Produk (Aktif)</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeaturedProd">
                        <label class="form-check-label small fw-semibold" for="isFeaturedProd">Jadikan Produk Unggulan</label>
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Harga & Stok</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Harga Normal (Rp) *</label>
                    <input type="number" name="price" class="form-control" required placeholder="Contoh: 850000" min="0">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Harga Diskon / Promo (Rp)</label>
                    <input type="number" name="discount_price" class="form-control" placeholder="Contoh: 785000" min="0">
                    <small class="text-muted">Biarkan kosong jika tidak ada promo harga.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Stok Unit *</label>
                    <input type="number" name="stock" class="form-control" value="10" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Berat Produk (Gram) *</label>
                    <input type="number" name="weight" class="form-control" value="500" min="1" required>
                    <small class="text-muted">Digunakan untuk estimasi pengiriman.</small>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                    <i class="bi bi-floppy-fill me-2"></i> Simpan Produk
                </button>
            </div>
        </div>
    </div>
</form>
