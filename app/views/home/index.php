<?php
use App\Helpers\Sanitizer;
use App\Helpers\SeoHelper;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyName = !empty($settings['site_name']) ? $settings['site_name'] : (!empty($settings['company_name']) ? $settings['company_name'] : 'Perusahaan Kami');
$companySlogan = $settings['company_slogan'] ?? '';
$companyShortDesc = $settings['company_short_description'] ?? '';
$companyWa = $settings['company_whatsapp'] ?? '081234567890';
$aboutHomeImage = !empty($settings['company_about_image']) ? UrlHelper::upload($settings['company_about_image']) : 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80';
$badgeTitle = !empty($settings['company_badge_title']) ? $settings['company_badge_title'] : 'Infrastruktur Berstandar Enterprise';
$badgeDesc = !empty($settings['company_badge_desc']) ? $settings['company_badge_desc'] : 'Siap mendukung pertumbuhan jaringan kantor dan bisnis Anda.';
?>

<!-- Schema.org Organization -->
<?= SeoHelper::renderOrganizationSchema($settings) ?>

<!-- Hero Slider -->
<?php if (!empty($banners)): ?>
<section class="hero-slider mb-5">
    <div id="homeHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <?php foreach ($banners as $idx => $b): ?>
                <button type="button" data-bs-target="#homeHeroCarousel" data-bs-slide-to="<?= $idx ?>" class="<?= $idx === 0 ? 'active' : '' ?>" aria-label="Slide <?= $idx + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
        <div class="carousel-inner">
            <?php foreach ($banners as $idx => $b): ?>
                <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>" style="height: 480px; background-color: #101827; position: relative;">
                    <?php if (!empty($b['image'])): ?>
                        <img src="<?= UrlHelper::upload($b['image']) ?>" alt="<?= e($b['title']) ?>" style="height: 100%; width: 100%; object-fit: cover; opacity: 0.35;">
                    <?php else: ?>
                        <div class="w-100 h-100" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);"></div>
                    <?php endif; ?>
                    <div class="carousel-caption">
                        <span class="badge text-bg-primary px-3 py-2 mb-2 rounded-pill fw-semibold">Official Partner & Distributor</span>
                        <h1 class="hero-title text-white fw-bold"><?= e($b['title']) ?></h1>
                        <p class="hero-subtitle text-white"><?= e($b['subtitle']) ?></p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="<?= UrlHelper::base($b['button_url'] ?? '/produk') ?>" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
                                <i class="bi bi-box-seam me-1"></i> <?= e($b['button_text'] ?? 'Lihat Produk') ?>
                            </a>
                            <?php 
                            $isContactTarget = str_contains(strtolower($b['button_url'] ?? ''), 'kontak') || str_contains(strtolower($b['button_text'] ?? ''), 'hubungi');
                            if ($isContactTarget): 
                            ?>
                                <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                                    <i class="bi bi-grid me-1"></i> Katalog Produk
                                </a>
                            <?php else: ?>
                                <a href="<?= UrlHelper::base('kontak') ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                                    <i class="bi bi-telephone-fill me-1"></i> Hubungi Kami
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>
<?php endif; ?>

<!-- Quick Features / Keunggulan Perusahaan -->
<?php
$featuresEnabled = ($settings['features_enabled'] ?? '1') !== '0';
$featureCards = [
    [
        'icon' => $settings['feature_1_icon'] ?? 'bi-patch-check-fill',
        'title' => $settings['feature_1_title'] ?? '100% Produk Original',
        'desc' => $settings['feature_1_desc'] ?? 'Seluruh perangkat bergaransi resmi distributor dengan jaminan keaslian unit.',
    ],
    [
        'icon' => $settings['feature_2_icon'] ?? 'bi-headset',
        'title' => $settings['feature_2_title'] ?? 'Konsultasi Ahli IT',
        'desc' => $settings['feature_2_desc'] ?? 'Didukung teknisi berpengalaman untuk membantu konfigurasi topologi jaringan Anda.',
    ],
    [
        'icon' => $settings['feature_3_icon'] ?? 'bi-truck',
        'title' => $settings['feature_3_title'] ?? 'Pengiriman Cepat & Aman',
        'desc' => $settings['feature_3_desc'] ?? 'Pengemasan bubble wrap tebal dan opsi packing kayu untuk pengiriman seluruh nusantara.',
    ],
    [
        'icon' => $settings['feature_4_icon'] ?? 'bi-whatsapp',
        'title' => $settings['feature_4_title'] ?? 'Order Cepat via WhatsApp',
        'desc' => $settings['feature_4_desc'] ?? 'Pemesanan instan tanpa ribet, langsung terhubung ke admin penjualan resmi.',
    ],
];
?>
<?php if ($featuresEnabled): ?>
<section class="container py-4 mb-5">
    <div class="row g-4">
        <?php foreach ($featureCards as $fc): ?>
            <?php if (!empty($fc['title'])): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100">
                        <div class="feature-icon-wrapper">
                            <i class="bi <?= e($fc['icon'] ?: 'bi-check-circle-fill') ?>"></i>
                        </div>
                        <h5 class="fw-bold mb-2"><?= e($fc['title']) ?></h5>
                        <p class="text-secondary small mb-0"><?= e($fc['desc']) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- About Company Snippet -->
<section class="py-5 bg-white mb-5 border-top border-bottom">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="text-primary fw-bold text-uppercase small tracking-wide">Tentang Perusahaan</span>
                <?php if (!empty($companySlogan)): ?>
                    <h6 class="text-secondary mb-4 fst-italic">"<?= e($companySlogan) ?>"</h6>
                <?php endif; ?>
                <p class="text-muted leading-relaxed mb-4">
                    <?= nl2br(e($companyShortDesc)) ?>
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="border-start border-primary border-3 ps-3">
                            <h4 class="fw-bold text-dark mb-0"><?= e($settings['company_established_year'] ?? '2018') ?></h4>
                            <small class="text-muted">Tahun Berdiri</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border-start border-primary border-3 ps-3">
                            <h4 class="fw-bold text-dark mb-0">1.500+</h4>
                            <small class="text-muted">Pelanggan Puas</small>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <a href="<?= UrlHelper::base('tentang') ?>" class="btn btn-primary rounded-pill px-4">
                        Selengkapnya Tentang Kami <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="<?= WhatsAppHelper::getContactLink($companyWa) ?>" target="_blank" class="btn btn-outline-success rounded-pill px-4">
                        <i class="bi bi-whatsapp me-1"></i> Hubungi WhatsApp
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="<?= e($aboutHomeImage) ?>" alt="<?= e($badgeTitle) ?>" class="img-fluid rounded-4 shadow-lg w-100" style="max-height: 400px; object-fit: cover;" loading="lazy">
                    <div class="position-absolute bottom-0 start-0 bg-dark bg-opacity-90 text-white p-4 rounded-bottom-4 w-100">
                        <h6 class="fw-bold mb-1 text-white"><?= e($badgeTitle) ?></h6>
                        <small class="text-secondary"><?= e($badgeDesc) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Showcase -->
<?php if (!empty($categories)): ?>
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold text-uppercase small">Koleksi Produk</span>
            <h2 class="fw-bold text-dark mb-0">Kategori Pilihan</h2>
        </div>
        <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="row g-3">
        <?php foreach ($categories as $cat): ?>
            <div class="col-md-4 col-sm-6">
                <a href="<?= UrlHelper::base('kategori/' . $cat['slug']) ?>" class="category-card">
                    <div class="category-icon-box">
                        <?php if (!empty($cat['icon_image'])): ?>
                            <img src="<?= UrlHelper::upload($cat['icon_image']) ?>" alt="<?= e($cat['name']) ?>" style="max-height: 36px;">
                        <?php else: ?>
                            <i class="bi bi-router-fill"></i>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= e($cat['name']) ?></h5>
                    <span class="badge text-bg-light text-secondary rounded-pill px-3 py-1"><?= (int) $cat['product_count'] ?> Produk</span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-danger fw-bold text-uppercase small">Rekomendasi Terbaik</span>
            <h2 class="fw-bold text-dark mb-0">Produk Unggulan</h2>
        </div>
        <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            Katalog Lengkap <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($featuredProducts as $prod):
            $effectivePrice = ($prod['discount_price'] > 0) ? $prod['discount_price'] : $prod['price'];
            $hasDiscount = ($prod['discount_price'] > 0);
            $productUrl = UrlHelper::base('produk/' . $prod['slug']);
        ?>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="product-card">
                    <div class="product-img-wrap">
                        <a href="<?= $productUrl ?>" class="d-block w-100 h-100 text-center">
                            <img src="<?= UrlHelper::upload($prod['main_image'], 'assets/images/no-image.png') ?>" alt="<?= e($prod['name']) ?>" loading="lazy">
                        </a>
                        <?php if ($hasDiscount): ?>
                            <span class="product-badge-discount">HEMAT</span>
                        <?php endif; ?>
                        <span class="product-badge-featured"><i class="bi bi-star-fill text-warning"></i> Unggulan</span>
                    </div>
                    <div class="product-body">
                        <span class="product-category"><?= e($prod['category_name'] ?? 'Networking') ?></span>
                        <h6 class="product-title">
                            <a href="<?= $productUrl ?>"><?= e($prod['name']) ?></a>
                        </h6>
                        <div class="product-price-box mb-3">
                            <span class="product-price-current"><?= Sanitizer::formatRupiah($effectivePrice) ?></span>
                            <?php if ($hasDiscount): ?>
                                <span class="product-price-old"><?= Sanitizer::formatRupiah($prod['price']) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <form action="<?= UrlHelper::base('keranjang/tambah') ?>" method="POST" class="ajax-add-to-cart flex-grow-1">
                                <input type="hidden" name="product_id" value="<?= $prod['id'] ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100 rounded-pill" <?= ($prod['stock'] <= 0) ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-plus me-1"></i> Keranjang
                                </button>
                            </form>
                            <a href="<?= WhatsAppHelper::getProductInquiryLink($companyWa, $prod['name'], $effectivePrice, $productUrl) ?>" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3" title="Chat WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Promotional Banner -->
<?php
$promoEnabled = ($settings['promo_card_enabled'] ?? '1') !== '0';
$promoBadge = $settings['promo_card_badge'] ?? 'PENAWARAN KHUSUS BISNIS';
$promoTitle = $settings['promo_card_title'] ?? 'Butuh Pengadaan Perangkat Kantor Skala Besar?';
$promoDesc = $settings['promo_card_desc'] ?? 'Dapatkan penawaran harga khusus (B2B corporate rate) dengan invoice resmi dan dukungan teknis langsung.';
$promoBtnWaText = $settings['promo_card_btn_wa_text'] ?? 'Minta Penawaran Harga';
$promoWaMessage = $settings['promo_card_wa_message'] ?? 'Halo Admin, saya ingin meminta penawaran harga pengadaan perangkat IT kantor untuk perusahaan.';
$promoBtnSecondaryText = $settings['promo_card_btn_secondary_text'] ?? 'Kontak Perusahaan';
$promoBtnSecondaryUrl = $settings['promo_card_btn_secondary_url'] ?? 'kontak';
?>
<?php if ($promoEnabled): ?>
<section class="container mb-5">
    <div class="p-4 p-md-5 rounded-4 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <?php if (!empty($promoBadge)): ?>
                    <span class="badge text-bg-warning px-3 py-2 rounded-pill fw-bold mb-3"><?= e($promoBadge) ?></span>
                <?php endif; ?>
                <h2 class="display-6 fw-bold mb-3"><?= e($promoTitle) ?></h2>
                <p class="text-light text-opacity-75 lead mb-4"><?= e($promoDesc) ?></p>
                <div class="d-flex flex-wrap gap-3">
                    <?php if (!empty($promoBtnWaText)): ?>
                        <a href="<?= WhatsAppHelper::getContactLink($companyWa, $promoWaMessage) ?>" target="_blank" class="btn btn-success btn-lg rounded-pill px-4">
                            <i class="bi bi-whatsapp me-2"></i> <?= e($promoBtnWaText) ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($promoBtnSecondaryText)): ?>
                        <a href="<?= UrlHelper::base($promoBtnSecondaryUrl) ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <?= e($promoBtnSecondaryText) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="bi bi-shield-check text-warning" style="font-size: 8rem; opacity: 0.85;"></i>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Articles -->
<?php if (!empty($latestArticles)): ?>
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <span class="text-primary fw-bold text-uppercase small">Wawasan & Panduan</span>
            <h2 class="fw-bold text-dark mb-0">Artikel & Tips Terbaru</h2>
        </div>
        <a href="<?= UrlHelper::base('artikel') ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            Semua Artikel <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="row g-4">
        <?php foreach ($latestArticles as $art): ?>
            <div class="col-md-4">
                <div class="card h-100 border rounded-4 overflow-hidden shadow-sm">
                    <div style="height: 190px; background-color: #f1f5f9; overflow: hidden;">
                        <?php if (!empty($art['thumbnail'])): ?>
                            <img src="<?= UrlHelper::upload($art['thumbnail']) ?>" alt="<?= e($art['title']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                        <?php else: ?>
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 text-secondary">
                                <i class="bi bi-newspaper fs-1"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-direction-column">
                        <div class="small text-muted mb-2">
                            <i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($art['published_at'] ?? $art['created_at'])) ?> &bull; <?= e($art['category']) ?>
                        </div>
                        <h5 class="card-title fw-bold">
                            <a href="<?= UrlHelper::base('artikel/' . $art['slug']) ?>" class="text-dark text-decoration-none">
                                <?= e($art['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-secondary small">
                            <?= e(Sanitizer::truncate(strip_tags($art['content']), 110)) ?>
                        </p>
                        <a href="<?= UrlHelper::base('artikel/' . $art['slug']) ?>" class="text-primary fw-semibold small text-decoration-none mt-auto">
                            Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Gallery Snippet -->
<?php if (!empty($galleries)): ?>
<section class="container mb-5">
    <div class="text-center mb-4">
        <span class="text-primary fw-bold text-uppercase small">Dokumentasi</span>
        <h2 class="fw-bold text-dark mb-0">Galeri Fasilitas & Kegiatan</h2>
    </div>

    <div class="row g-3">
        <?php foreach ($galleries as $gal): ?>
            <div class="col-md-4 col-6">
                <div class="rounded-4 overflow-hidden position-relative shadow-sm" style="height: 200px; background: #e2e8f0;">
                    <?php if (!empty($gal['image'])): ?>
                        <img src="<?= UrlHelper::upload($gal['image']) ?>" alt="<?= e($gal['title']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                    <?php else: ?>
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    <?php endif; ?>
                    <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2 small text-center">
                        <?= e($gal['title']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-3">
        <a href="<?= UrlHelper::base('galeri') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
            Lihat Semua Foto Galeri <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>
<?php endif; ?>

<!-- Bottom WhatsApp CTA Section -->
<section class="py-5 bg-light text-center border-top">
    <div class="container py-3">
        <i class="bi bi-whatsapp text-success display-4 mb-3 d-inline-block"></i>
        <h2 class="fw-bold text-dark">Konsultasikan Kebutuhan Anda Sekarang</h2>
        <p class="text-secondary mx-auto mb-4" style="max-width: 600px;">
            Bingung memilih spesifikasi router atau switch yang tepat untuk kantor? Hubungi sales engineer kami melalui WhatsApp untuk rekomendasi terbaik dan stok terkini.
        </p>
        <a href="<?= WhatsAppHelper::getContactLink($companyWa, "Halo Admin, saya ingin konsultasi kebutuhan perangkat jaringan untuk kantor kami.") ?>" target="_blank" class="btn btn-success btn-lg rounded-pill px-5 shadow">
            <i class="bi bi-whatsapp me-2"></i> Chat WhatsApp Sekarang
        </a>
    </div>
</section>
