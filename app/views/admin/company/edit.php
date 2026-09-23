<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Profil Perusahaan</h3>
        <p class="text-secondary small mb-0">Ubah seluruh data identitas, visi misi, dan kontak perusahaan tanpa edit kode.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= UrlHelper::base('admin/teams') ?>" class="btn btn-outline-success btn-sm rounded-pill px-3">
            <i class="bi bi-people-fill me-1"></i> Kelola Tim & Pengurus
        </a>
        <a href="<?= UrlHelper::base('tentang') ?>" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Pratinjau Tentang Kami
        </a>
        <a href="<?= UrlHelper::base('kontak') ?>" target="_blank" class="btn btn-outline-info btn-sm rounded-pill px-3">
            <i class="bi bi-eye me-1"></i> Pratinjau Kontak
        </a>
    </div>
</div>

<form action="<?= UrlHelper::base('admin/company/update') ?>" method="POST" enctype="multipart/form-data">
    <?= CsrfHelper::field() ?>

    <div class="row g-4">
        <!-- Main Identity -->
        <div class="col-lg-8">
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Identitas Pokok Perusahaan</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Perusahaan *</label>
                    <input type="text" name="company_name" class="form-control" value="<?= e($company['company_name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Slogan / Tagline Bisnis</label>
                    <input type="text" name="company_slogan" class="form-control" value="<?= e($company['company_slogan'] ?? '') ?>">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Tahun Berdiri</label>
                        <input type="text" name="company_established_year" class="form-control" value="<?= e($company['company_established_year'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Perusahaan</label>
                        <input type="email" name="company_email" class="form-control" value="<?= e($company['company_email'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Singkat (Tampil di Homepage & Footer)</label>
                    <textarea name="company_short_description" rows="3" class="form-control"><?= e($company['company_short_description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Lengkap (Halaman Tentang Kami)</label>
                    <textarea name="company_long_description" rows="6" class="form-control"><?= e($company['company_long_description'] ?? '') ?></textarea>
                    <small class="text-muted">Mendukung format HTML sederhana seperti &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;.</small>
                </div>
            </div>

            <!-- Visi, Misi, Nilai, Keunggulan -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Visi, Misi & Keunggulan</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Visi Perusahaan</label>
                    <textarea name="company_vision" rows="3" class="form-control"><?= e($company['company_vision'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Misi Perusahaan</label>
                    <textarea name="company_mission" rows="4" class="form-control"><?= e($company['company_mission'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nilai-Nilai Perusahaan (Company Values)</label>
                    <textarea name="company_values" rows="3" class="form-control"><?= e($company['company_values'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Keunggulan Perusahaan</label>
                    <textarea name="company_advantages" rows="4" class="form-control"><?= e($company['company_advantages'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Seksi Judul & Keterangan Tim Manajemen -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Teks Seksi Tim Manajemen & Pimpinan</h5>
                    <a href="<?= UrlHelper::base('admin/teams') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-person-lines-fill me-1"></i> Buka Manajemen Tim
                    </a>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Label / Badge Seksi</label>
                        <input type="text" name="company_team_badge" class="form-control" value="<?= e($company['company_team_badge'] ?? 'Kepemimpinan & Pengurus') ?>">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold">Judul Seksi Tim</label>
                        <input type="text" name="company_team_title" class="form-control" value="<?= e($company['company_team_title'] ?? 'Tim Manajemen & Pimpinan / Pengurus') ?>">
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold">Deskripsi / Sub-judul Seksi</label>
                    <textarea name="company_team_subtitle" rows="3" class="form-control"><?= e($company['company_team_subtitle'] ?? 'Kelola daftar jajaran pimpinan, dewan direksi, dan pengurus perusahaan yang tampil di halaman profil (Tentang Kami).') ?></textarea>
                </div>
            </div>

            <!-- Card Penawaran Khusus Bisnis (B2B) -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4" id="penawaran-bisnis">
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                            <i class="bi bi-briefcase-fill fs-5 text-dark"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Card Penawaran Khusus Bisnis (B2B)</h5>
                            <small class="text-secondary">Banner promosi penawaran khusus / pengadaan yang tampil di halaman Beranda.</small>
                        </div>
                    </div>
                    <div>
                        <select name="promo_card_enabled" class="form-select form-select-sm fw-semibold">
                            <option value="1" <?= ($company['promo_card_enabled'] ?? '1') !== '0' ? 'selected' : '' ?>>Tampilkan</option>
                            <option value="0" <?= ($company['promo_card_enabled'] ?? '1') === '0' ? 'selected' : '' ?>>Sembunyikan</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Label Badge</label>
                        <input type="text" name="promo_card_badge" class="form-control" value="<?= e($company['promo_card_badge'] ?? 'PENAWARAN KHUSUS BISNIS') ?>" placeholder="Contoh: PENAWARAN KHUSUS BISNIS">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small fw-semibold">Judul Utama Penawaran *</label>
                        <input type="text" name="promo_card_title" class="form-control" value="<?= e($company['promo_card_title'] ?? 'Butuh Pengadaan Perangkat Kantor Skala Besar?') ?>" placeholder="Contoh: Butuh Pengadaan Perangkat Kantor Skala Besar?">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi / Keterangan Penawaran</label>
                    <textarea name="promo_card_desc" rows="2" class="form-control" placeholder="Contoh: Dapatkan penawaran harga khusus (B2B corporate rate) dengan invoice resmi dan dukungan teknis langsung."><?= e($company['promo_card_desc'] ?? 'Dapatkan penawaran harga khusus (B2B corporate rate) dengan invoice resmi dan dukungan teknis langsung.') ?></textarea>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold"><i class="bi bi-whatsapp text-success me-1"></i> Teks Tombol WhatsApp</label>
                        <input type="text" name="promo_card_btn_wa_text" class="form-control" value="<?= e($company['promo_card_btn_wa_text'] ?? 'Minta Penawaran Harga') ?>" placeholder="Minta Penawaran Harga">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold"><i class="bi bi-chat-text me-1"></i> Template Pesan WhatsApp Otomatis</label>
                        <input type="text" name="promo_card_wa_message" class="form-control" value="<?= e($company['promo_card_wa_message'] ?? 'Halo Admin, saya ingin meminta penawaran harga pengadaan perangkat IT kantor untuk perusahaan.') ?>" placeholder="Pesan otomatis saat user klik tombol WA">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold"><i class="bi bi-link-45deg me-1"></i> Teks Tombol Sekunder</label>
                        <input type="text" name="promo_card_btn_secondary_text" class="form-control" value="<?= e($company['promo_card_btn_secondary_text'] ?? 'Kontak Perusahaan') ?>" placeholder="Kontak Perusahaan">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Link Tujuan Tombol Sekunder</label>
                        <input type="text" name="promo_card_btn_secondary_url" class="form-control" value="<?= e($company['promo_card_btn_secondary_url'] ?? 'kontak') ?>" placeholder="kontak">
                        <small class="text-muted">Isi dengan slug seperti <code>kontak</code> atau URL lengkap.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact & Media Sidebar -->
        <div class="col-lg-4">
            <!-- Logos -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark">Logo & Favicon</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Logo Perusahaan</label>
                    <?php if (!empty($company['site_logo'])): ?>
                        <div class="mb-2 p-2 bg-light border rounded text-center">
                            <img src="<?= UrlHelper::upload($company['site_logo']) ?>" alt="Logo" style="max-height: 50px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="site_logo" class="form-control form-control-sm" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Format: PNG, JPG, WEBP (Maks 2MB)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Favicon Browser</label>
                    <?php if (!empty($company['site_favicon'])): ?>
                        <div class="mb-2 p-2 bg-light border rounded text-center">
                            <img src="<?= UrlHelper::upload($company['site_favicon']) ?>" alt="Favicon" style="max-height: 32px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="site_favicon" class="form-control form-control-sm" accept="image/png, image/jpeg, image/webp">
                </div>
            </div>

            <!-- About Page Media & Badge -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-image me-2 text-primary"></i>Foto & Badge Profil (Beranda & Tentang Kami)</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Foto Profil / Tentang Kami</label>
                    <?php if (!empty($company['company_about_image'])): ?>
                        <div class="mb-2 p-2 bg-light border rounded text-center">
                            <img src="<?= UrlHelper::upload($company['company_about_image']) ?>" alt="About Photo" class="img-fluid rounded" style="max-height: 140px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="company_about_image" class="form-control form-control-sm" accept="image/png, image/jpeg, image/webp">
                    <small class="text-muted">Format: JPG, PNG, WEBP (Rekomendasi 800x500px). Foto ini ditampilkan pada bagian Tentang Perusahaan di halaman Beranda &amp; Tentang Kami.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Judul Badge Foto</label>
                    <input type="text" name="company_badge_title" class="form-control form-control-sm" value="<?= e($company['company_badge_title'] ?? 'Berpengalaman & Terpercaya') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Sub-teks Badge Foto</label>
                    <input type="text" name="company_badge_desc" class="form-control form-control-sm" value="<?= e($company['company_badge_desc'] ?? 'Pelayanan Terbaik & Profesional') ?>" placeholder="Contoh: Melayani Masyarakat & Mitra Usaha">
                </div>
            </div>

            <!-- Contact Page Header & Texts -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4" id="kontak-teks">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-fonts fs-6"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Judul & Keterangan Halaman Kontak</h5>
                        <small class="text-secondary">Teks pembuka header yang tampil di halaman Hubungi Kami (<code>/kontak</code>).</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Label / Badge Seksi</label>
                    <input type="text" name="contact_page_badge" class="form-control form-control-sm" value="<?= e($company['contact_page_badge'] ?? 'Bantuan & Layanan') ?>" placeholder="Contoh: Bantuan & Layanan">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Judul Halaman Kontak *</label>
                    <input type="text" name="contact_page_title" class="form-control form-control-sm" value="<?= e($company['contact_page_title'] ?? 'Hubungi Kami') ?>" placeholder="Contoh: Hubungi Kami">
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-semibold">Kata-kata Keterangan / Deskripsi Sub-judul</label>
                    <textarea name="contact_page_subtitle" rows="3" class="form-control form-control-sm" placeholder="Tuliskan keterangan / pengantar di bawah judul..."><?= e($company['contact_page_subtitle'] ?? 'Kami siap membantu menjawab kebutuhan teknologi jaringan, stok produk, serta permintaan penawaran harga resmi perusahaan Anda.') ?></textarea>
                </div>
            </div>

            <!-- Contacts & Hours -->
            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4" id="kontak">
                <h5 class="fw-bold mb-3 text-dark">Kontak & Lokasi</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nomor WhatsApp * (Untuk Checkout & CS)</label>
                    <input type="text" name="company_whatsapp" class="form-control" value="<?= e($company['company_whatsapp'] ?? '') ?>" required>
                    <small class="text-muted">Gunakan format 0812xxx atau 628xxx</small>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nomor Telepon Kantor</label>
                    <input type="text" name="company_phone" class="form-control" value="<?= e($company['company_phone'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Alamat Lengkap Kantor</label>
                    <textarea name="company_address" rows="3" class="form-control"><?= e($company['company_address'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Jam Operasional</label>
                    <textarea name="company_hours" rows="3" class="form-control"><?= e($company['company_hours'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Embed Google Maps (&lt;iframe&gt;)</label>
                    <textarea name="company_maps_embed" rows="3" class="form-control small"><?= e($company['company_maps_embed'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow">
                    <i class="bi bi-floppy-fill me-2"></i> Simpan Perubahan Profil
                </button>
            </div>
        </div>
    </div>
</form>
