<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyName = !empty($settings['company_name']) ? $settings['company_name'] : (!empty($settings['site_name']) ? $settings['site_name'] : 'Perusahaan Kami');
$companyWa = $settings['company_whatsapp'] ?? '081234567890';
$companyEmail = $settings['company_email'] ?? 'info@solusitekno.co.id';
$companyPhone = $settings['company_phone'] ?? '(021) 7890-1234';
$companyAddress = $settings['company_address'] ?? 'Jakarta Selatan';

$footerText = !empty($settings['footer_text']) ? $settings['footer_text'] : ($settings['company_short_description'] ?? 'Mitra terpercaya untuk kebutuhan Anda.');
if (str_contains($footerText, 'PT Solusi Tekno Nusantara')) {
    $footerText = str_replace('PT Solusi Tekno Nusantara', $companyName, $footerText);
}

// Footer copyright diambil langsung dari nama perusahaan
$footerCopyright = '© ' . date('Y') . ' ' . $companyName . '. All Rights Reserved.';

$footerTagline = (!empty($settings['site_tagline']) && strcasecmp($settings['site_tagline'], $companyName) !== 0)
    ? $settings['site_tagline']
    : (!empty($settings['company_slogan']) && strcasecmp($settings['company_slogan'], $companyName) !== 0 && !str_contains($settings['company_slogan'], 'Menghubungkan Bisnis Anda') ? $settings['company_slogan'] : '');
?>
</main>

<!-- Footer -->
<footer class="pt-5 pb-4 mt-5 border-top">
    <div class="container">
        <div class="row g-4">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-title"><?= e($companyName) ?></h5>
                <p class="small text-secondary mb-3 pe-lg-4"><?= e($footerText) ?></p>
                <div class="small text-secondary d-flex flex-column gap-2">
                    <div><i class="bi bi-geo-alt text-primary me-2"></i> <?= e($companyAddress) ?></div>
                    <div><i class="bi bi-telephone text-primary me-2"></i> <?= e($companyPhone) ?></div>
                    <div><i class="bi bi-whatsapp text-success me-2"></i> <?= e($companyWa) ?></div>
                    <div><i class="bi bi-envelope text-primary me-2"></i> <?= e($companyEmail) ?></div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="footer-title">Navigasi</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="<?= UrlHelper::base() ?>">Home</a></li>
                    <li><a href="<?= UrlHelper::base('tentang') ?>">Tentang Kami</a></li>
                    <li><a href="<?= UrlHelper::base('produk') ?>">Katalog Produk</a></li>
                    <li><a href="<?= UrlHelper::base('artikel') ?>">Artikel & Berita</a></li>
                    <li><a href="<?= UrlHelper::base('galeri') ?>">Galeri Foto</a></li>
                    <li><a href="<?= UrlHelper::base('kontak') ?>">Hubungi Kami</a></li>
                </ul>
            </div>

            <!-- Customer Care -->
            <div class="col-lg-3 col-md-6 col-6">
                <h5 class="footer-title">Bantuan & Legal</h5>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="<?= UrlHelper::base('faq') ?>">Pertanyaan Umum (FAQ)</a></li>
                    <li><a href="<?= UrlHelper::base('kebijakan-privasi') ?>">Kebijakan Privasi</a></li>
                    <li><a href="<?= UrlHelper::base('syarat-ketentuan') ?>">Syarat & Ketentuan</a></li>
                    <li><a href="<?= UrlHelper::base('keranjang') ?>">Keranjang Belanja</a></li>
                    <li><a href="<?= UrlHelper::base('admin/login') ?>" target="_blank" class="text-muted"><i class="bi bi-lock me-1"></i> Area Admin</a></li>
                </ul>
            </div>

            <!-- Social & Jam Operasional -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-title">Jam Operasional</h5>
                <p class="small text-secondary mb-3">
                    <?= nl2br(e($settings['company_hours'] ?? "Senin - Jumat: 08:30 - 17:30 WIB\nSabtu: 09:00 - 15:00 WIB")) ?>
                </p>
                <h6 class="text-white small fw-bold mb-2">Ikuti Kami:</h6>
                <div class="d-flex gap-2">
                    <?php if (!empty($settings['social_facebook'])): ?>
                        <a href="<?= e($settings['social_facebook']) ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_instagram'])): ?>
                        <a href="<?= e($settings['social_instagram']) ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_tiktok'])): ?>
                        <a href="<?= e($settings['social_tiktok']) ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-tiktok"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_youtube'])): ?>
                        <a href="<?= e($settings['social_youtube']) ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-youtube"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr class="border-secondary border-opacity-25 my-4">

        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-start small text-secondary">
                <?= e($footerCopyright) ?>
            </div>
            <div class="col-md-4 text-center text-md-end small text-secondary mt-2 mt-md-0">
                <?php if (!empty($footerTagline)): ?>
                    <span><?= e($footerTagline) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Button -->
<a href="<?= WhatsAppHelper::getContactLink($companyWa) ?>" target="_blank" class="floating-wa-btn shadow" title="Chat Sales via WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- App Global Variables -->
<script>
    window.APP_URL = "<?= UrlHelper::base() ?>";
</script>

<!-- Main JS -->
<script src="<?= UrlHelper::asset('js/main.js') ?>"></script>

</body>
</html>
