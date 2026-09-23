<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyName = !empty($settings['site_name']) ? $settings['site_name'] : (!empty($settings['company_name']) ? $settings['company_name'] : 'Perusahaan Kami');
$companySlogan = $settings['company_slogan'] ?? '';
$companyWa = $settings['company_whatsapp'] ?? '081234567890';
$companyEmail = $settings['company_email'] ?? 'info@solusitekno.co.id';
$companyPhone = $settings['company_phone'] ?? '(021) 7890-1234';
$companyAddress = $settings['company_address'] ?? '';
$companyYear = $settings['company_established_year'] ?? '2018';
$vision = $settings['company_vision'] ?? '';
$mission = $settings['company_mission'] ?? '';
$values = $settings['company_values'] ?? '';
$advantages = $settings['company_advantages'] ?? '';
$maps = $settings['company_maps_embed'] ?? '';

$aboutImage = !empty($settings['company_about_image']) ? UrlHelper::upload($settings['company_about_image']) : 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80';
$badgeTitle = !empty($settings['company_badge_title']) ? $settings['company_badge_title'] : 'Berpengalaman & Terpercaya';
$badgeDesc = !empty($settings['company_badge_desc']) ? $settings['company_badge_desc'] : 'Pelayanan Terbaik & Profesional';
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tentang Kami</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <!-- Header Hero -->
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="badge text-bg-primary px-3 py-2 rounded-pill mb-2">Profil Perusahaan</span>
            <h1 class="display-5 fw-bold text-dark mb-3"><?= e($companyName) ?></h1>
            <?php if (!empty($companySlogan)): ?>
                <p class="lead text-primary fw-semibold mb-4">"<?= e($companySlogan) ?>"</p>
            <?php endif; ?>
            <div class="text-secondary leading-relaxed mb-4">
                <?= $settings['company_long_description'] ?? '' ?>
            </div>
            <div class="d-flex gap-3">
                <a href="<?= WhatsAppHelper::getContactLink($companyWa) ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-whatsapp me-2"></i> Hubungi WhatsApp
                </a>
                <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-outline-primary rounded-pill px-4">
                    Lihat Katalog Produk
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="position-relative rounded-4 overflow-hidden shadow-lg border">
                <img src="<?= e($aboutImage) ?>" alt="<?= e($companyName) ?>" class="img-fluid w-100 d-block" style="height: 420px; object-fit: cover;">
                <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.9) 100%);">
                    <div class="card border-0 p-3 rounded-3 shadow d-flex flex-row align-items-center gap-3" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px);">
                        <div class="bg-primary text-white rounded-3 p-2 px-3 text-center flex-shrink-0" style="min-width: 65px;">
                            <h4 class="fw-bold mb-0"><?= e($companyYear) ?></h4>
                            <small class="small">Berdiri</small>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><?= e($badgeTitle) ?></h6>
                            <?php if (!empty($badgeDesc)): ?>
                                <small class="text-secondary"><?= e($badgeDesc) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visi, Misi & Nilai -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="p-4 p-lg-5 bg-light rounded-4 h-100 border">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-eye-fill fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Visi Kami</h3>
                </div>
                <p class="text-secondary mb-0 leading-relaxed">
                    <?= nl2br(e($vision)) ?>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 p-lg-5 bg-light rounded-4 h-100 border">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="bi bi-bullseye fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Misi Kami</h3>
                </div>
                <div class="text-secondary mb-0 leading-relaxed">
                    <?= nl2br(e($mission)) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai & Keunggulan -->
    <div class="row g-4 mb-5">
        <div class="col-lg-6">
            <div class="card h-100 border rounded-4 p-4 shadow-sm">
                <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-gem text-primary me-2"></i> Nilai-Nilai Perusahaan</h4>
                <p class="text-secondary leading-relaxed">
                    <?= nl2br(e($values)) ?>
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100 border rounded-4 p-4 shadow-sm">
                <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-trophy-fill text-warning me-2"></i> Keunggulan Utama</h4>
                <div class="text-secondary leading-relaxed">
                    <?= nl2br(e($advantages)) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Tim Manajemen & Pimpinan / Pengurus -->
    <?php 
    $teamList = !empty($teams) ? $teams : (class_exists('App\Models\Team') ? App\Models\Team::getActive() : []);
    $teamTitle = class_exists('App\Models\Setting') ? App\Models\Setting::get('company_team_title', $settings['company_team_title'] ?? 'Tim Manajemen & Pimpinan / Pengurus') : ($settings['company_team_title'] ?? 'Tim Manajemen & Pimpinan / Pengurus');
    $teamBadge = class_exists('App\Models\Setting') ? App\Models\Setting::get('company_team_badge', $settings['company_team_badge'] ?? 'Kepemimpinan & Pengurus') : ($settings['company_team_badge'] ?? 'Kepemimpinan & Pengurus');
    $teamSubtitle = class_exists('App\Models\Setting') ? App\Models\Setting::get('company_team_subtitle', $settings['company_team_subtitle'] ?? 'Kelola daftar jajaran pimpinan, dewan direksi, dan pengurus perusahaan yang tampil di halaman profil (Tentang Kami).') : ($settings['company_team_subtitle'] ?? 'Kelola daftar jajaran pimpinan, dewan direksi, dan pengurus perusahaan yang tampil di halaman profil (Tentang Kami).');
    ?>
    <div class="mb-5">
        <div class="text-center mb-5">
            <?php if (!empty($teamBadge)): ?>
                <span class="badge text-bg-primary px-3 py-2 rounded-pill mb-2">
                    <i class="bi bi-people-fill me-1"></i> <?= e($teamBadge) ?>
                </span>
            <?php endif; ?>
            <h2 class="display-6 fw-bold text-dark mb-2"><?= e($teamTitle) ?></h2>
            <?php if (!empty($teamSubtitle)): ?>
                <p class="text-secondary mx-auto" style="max-width: 680px;">
                    <?= nl2br(e($teamSubtitle)) ?>
                </p>
            <?php endif; ?>
        </div>

        <?php if (!empty($teamList)): ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($teamList as $member): ?>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 border rounded-4 shadow-sm text-center bg-white p-3 team-card overflow-hidden">
                            <div class="pt-3 pb-2">
                                <?php if (!empty($member['photo'])): ?>
                                    <img src="<?= UrlHelper::upload($member['photo']) ?>" alt="<?= e($member['name']) ?>" class="rounded-circle border border-3 border-primary border-opacity-25 shadow-sm object-fit-cover mx-auto d-block" style="width: 130px; height: 130px;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary border border-3 border-primary border-opacity-25 shadow-sm d-flex align-items-center justify-content-center mx-auto fw-bold" style="width: 130px; height: 130px; font-size: 2.8rem;">
                                        <?= strtoupper(mb_substr($member['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1 mb-2 fw-semibold" style="font-size: 0.8rem;">
                                        <?= e($member['position']) ?>
                                    </span>
                                    <h5 class="fw-bold text-dark mb-2 fs-6"><?= e($member['name']) ?></h5>
                                    <?php if (!empty($member['bio'])): ?>
                                        <p class="text-secondary small leading-relaxed mb-0" style="font-size: 0.825rem;">
                                            <?= nl2br(e($member['bio'])) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card border rounded-4 p-4 text-center text-muted bg-light">
                <i class="bi bi-people fs-1 text-secondary opacity-50 mb-2"></i>
                <p class="mb-0">Daftar tim manajemen dan pimpinan sedang diperbarui.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lokasi & Kontak -->
    <div class="card border rounded-4 p-4 p-lg-5 shadow-sm">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <h3 class="fw-bold text-dark mb-3">Kantor & Pusat Layanan</h3>
                <p class="text-secondary mb-4"><?= e($companyAddress) ?></p>
                <ul class="list-unstyled d-flex flex-column gap-3 mb-4">
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-whatsapp text-success fs-5"></i>
                        <span><?= e($companyWa) ?></span>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-telephone text-primary fs-5"></i>
                        <span><?= e($companyPhone) ?></span>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-envelope text-primary fs-5"></i>
                        <span><?= e($companyEmail) ?></span>
                    </li>
                    <li class="d-flex align-items-center gap-3">
                        <i class="bi bi-clock text-primary fs-5"></i>
                        <span><?= nl2br(e($settings['company_hours'] ?? '')) ?></span>
                    </li>
                </ul>
                <a href="<?= WhatsAppHelper::getContactLink($companyWa) ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-whatsapp me-2"></i> Hubungi WhatsApp Sekarang
                </a>
            </div>
            <div class="col-lg-7">
                <?php if ($maps): ?>
                    <div class="rounded-4 overflow-hidden shadow-sm" style="min-height: 350px;">
                        <?= $maps ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.team-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.team-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08) !important;
}
</style>
