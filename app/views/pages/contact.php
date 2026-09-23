<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;
use App\Models\Setting;

$companyName = !empty($settings['site_name']) ? $settings['site_name'] : (!empty($settings['company_name']) ? $settings['company_name'] : 'Perusahaan Kami');
$companyWa = $settings['company_whatsapp'] ?? '081234567890';
$companyEmail = $settings['company_email'] ?? 'info@solusitekno.co.id';
$companyPhone = $settings['company_phone'] ?? '(021) 7890-1234';
$companyAddress = $settings['company_address'] ?? '';
$companyHours = $settings['company_hours'] ?? '';
$maps = $settings['company_maps_embed'] ?? '';

$badge = $contactBadge ?? (class_exists('App\Models\Setting') ? Setting::get('contact_page_badge', 'Bantuan & Layanan') : 'Bantuan & Layanan');
$pageTitle = $contactTitle ?? (class_exists('App\Models\Setting') ? Setting::get('contact_page_title', 'Hubungi Kami') : 'Hubungi Kami');
$pageSubtitle = $contactSubtitle ?? (class_exists('App\Models\Setting') ? Setting::get('contact_page_subtitle', 'Kami siap membantu menjawab kebutuhan teknologi jaringan, stok produk, serta permintaan penawaran harga resmi perusahaan Anda.') : 'Kami siap membantu menjawab kebutuhan teknologi jaringan, stok produk, serta permintaan penawaran harga resmi perusahaan Anda.');
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kontak</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="text-center mb-5">
        <?php if (!empty($badge)): ?>
            <span class="badge text-bg-primary px-3 py-2 rounded-pill mb-2"><?= e($badge) ?></span>
        <?php endif; ?>
        <h1 class="fw-bold text-dark"><?= e($pageTitle) ?></h1>
        <?php if (!empty($pageSubtitle)): ?>
            <p class="text-secondary mx-auto" style="max-width: 600px;">
                <?= nl2br(e($pageSubtitle)) ?>
            </p>
        <?php endif; ?>
    </div>

    <div class="row g-5">
        <!-- Contact Info & WhatsApp CTA -->
        <div class="col-lg-5">
            <div class="card border rounded-4 p-4 shadow-sm h-100">
                <h4 class="fw-bold mb-4 text-dark">Informasi Kontak</h4>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Alamat Kantor</h6>
                        <p class="text-secondary small mb-0"><?= e($companyAddress) ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-whatsapp fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">WhatsApp Sales & Support</h6>
                        <p class="text-secondary small mb-1"><?= e($companyWa) ?></p>
                        <a href="<?= WhatsAppHelper::getContactLink($companyWa) ?>" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                            <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-envelope-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Perusahaan</h6>
                        <p class="text-secondary small mb-0"><?= e($companyEmail) ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="bi bi-clock-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Jam Operasional</h6>
                        <p class="text-secondary small mb-0"><?= nl2br(e($companyHours)) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card border rounded-4 p-4 p-lg-5 shadow-sm">
                <h4 class="fw-bold mb-3 text-dark">Kirim Pesan Langsung</h4>
                <p class="text-secondary small mb-4">Isi formulir berikut dan tim konsultan IT kami akan merespons pesan Anda sesegera mungkin.</p>

                <form action="<?= UrlHelper::base('kontak/kirim') ?>" method="POST">
                    <?= CsrfHelper::field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Lengkap *</label>
                            <input type="text" name="name" class="form-control rounded-3" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nomor WhatsApp *</label>
                            <input type="text" name="phone" class="form-control rounded-3" required placeholder="Contoh: 081234567890">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Alamat Email</label>
                            <input type="email" name="email" class="form-control rounded-3" placeholder="nama@email.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Subjek / Topik</label>
                            <input type="text" name="subject" class="form-control rounded-3" placeholder="Konsultasi Produk / Penawaran">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Pesan Anda *</label>
                            <textarea name="message" rows="5" class="form-control rounded-3" required placeholder="Tuliskan kebutuhan atau pertanyaan Anda di sini..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="bi bi-send-fill me-1"></i> Kirim Pesan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Google Maps Embed -->
    <?php if (!empty($maps)): ?>
        <div class="mt-5">
            <h4 class="fw-bold text-dark mb-3">Peta Lokasi Kantor</h4>
            <div class="rounded-4 overflow-hidden shadow-sm border">
                <?= $maps ?>
            </div>
        </div>
    <?php endif; ?>
</div>
