<?php
use App\Helpers\Sanitizer;
use App\Helpers\SeoHelper;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyName = !empty($settings['site_name']) ? $settings['site_name'] : (!empty($settings['company_name']) ? $settings['company_name'] : 'Perusahaan Kami');
$companyWa = $settings['company_whatsapp'] ?? '081234567890';
$companyEmail = $settings['company_email'] ?? 'info@solusitekno.co.id';
$companyHours = $settings['company_hours'] ?? 'Senin - Jumat: 08:30 - 17:30 WIB';
$logo = !empty($settings['site_logo']) ? UrlHelper::upload($settings['site_logo']) : '';
$favicon = !empty($settings['site_favicon']) ? UrlHelper::upload($settings['site_favicon']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Anti-FOUT Theme Initializer -->
    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const theme = saved ? saved : 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <?php if ($favicon): ?>
        <link rel="icon" type="image/png" href="<?= e($favicon) ?>">
    <?php endif; ?>

    <?= SeoHelper::renderMeta([
        'title' => $title ?? '',
        'site_name' => $companyName,
        'site_tagline' => $settings['site_tagline'] ?? '',
        'is_home' => !empty($is_home),
        'description' => $settings['site_description'] ?? '',
        'keywords' => $settings['site_keywords'] ?? '',
    ]) ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?= UrlHelper::asset('css/style.css') ?>">
</head>
<body>

<!-- Top Info Bar -->
<div class="py-2 small d-none d-lg-block border-bottom" style="background-color: #090e1a; border-color: rgba(255, 255, 255, 0.08) !important;">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3 text-light">
            <span><i class="bi bi-geo-alt text-info me-1"></i> <?= e($settings['company_address'] ?? 'Jakarta Selatan') ?></span>
            <span><i class="bi bi-envelope text-info me-1"></i> <?= e($companyEmail) ?></span>
            <span><i class="bi bi-clock text-info me-1"></i> <?= e(explode("\n", $companyHours)[0] ?? '') ?></span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if (!empty($settings['social_facebook'])): ?>
                <a href="<?= e($settings['social_facebook']) ?>" target="_blank" class="text-light opacity-75"><i class="bi bi-facebook"></i></a>
            <?php endif; ?>
            <?php if (!empty($settings['social_instagram'])): ?>
                <a href="<?= e($settings['social_instagram']) ?>" target="_blank" class="text-light opacity-75"><i class="bi bi-instagram"></i></a>
            <?php endif; ?>
            <?php if (!empty($settings['social_youtube'])): ?>
                <a href="<?= e($settings['social_youtube']) ?>" target="_blank" class="text-light opacity-75"><i class="bi bi-youtube"></i></a>
            <?php endif; ?>
            <a href="<?= WhatsAppHelper::getContactLink($companyWa) ?>" target="_blank" class="badge text-bg-success text-decoration-none px-2 py-1">
                <i class="bi bi-whatsapp me-1"></i> WhatsApp Sales
            </a>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark main-navbar sticky-top shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= UrlHelper::base() ?>">
            <?php if ($logo): ?>
                <img src="<?= e($logo) ?>" alt="<?= e($companyName) ?>" height="40" class="d-inline-block">
            <?php else: ?>
                <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-hdd-network-fill fs-5"></i>
                </div>
            <?php endif; ?>
            <span class="text-white fw-bold"><?= e($companyName) ?></span>
        </a>

        <!-- Mobile Buttons (Theme Toggle + Cart + Toggler) -->
        <div class="d-flex align-items-center gap-2 d-lg-none">
            <button class="btn btn-outline-light btn-sm rounded-circle theme-toggle-btn p-0 d-flex align-items-center justify-content-center" type="button" aria-label="Ganti Tema" title="Ganti Mode Gelap/Terang" style="width: 34px; height: 34px;">
                <i class="bi bi-moon-stars-fill theme-icon-moon"></i>
                <i class="bi bi-sun-fill theme-icon-sun d-none"></i>
            </button>
            <a href="<?= UrlHelper::base('keranjang') ?>" class="btn btn-outline-light position-relative btn-sm px-2">
                <i class="bi bi-cart3 fs-6"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge-count" style="display: <?= ($cart_count > 0) ? 'inline-block' : 'none' ?>;">
                    <?= (int) $cart_count ?>
                </span>
            </a>
            <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Nav Links -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('/') ?>" href="<?= UrlHelper::base() ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('tentang') ?>" href="<?= UrlHelper::base('tentang') ?>">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('produk') ?>" href="<?= UrlHelper::base('produk') ?>">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('artikel') ?>" href="<?= UrlHelper::base('artikel') ?>">Artikel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('galeri') ?>" href="<?= UrlHelper::base('galeri') ?>">Galeri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('faq') ?>" href="<?= UrlHelper::base('faq') ?>">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= UrlHelper::isActive('kontak') ?>" href="<?= UrlHelper::base('kontak') ?>">Kontak</a>
                </li>
                <li class="nav-item ms-lg-2 d-none d-lg-block">
                    <a href="<?= UrlHelper::base('keranjang') ?>" class="btn btn-outline-light position-relative rounded-pill px-3">
                        <i class="bi bi-cart3 me-1"></i> Keranjang
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge-count" style="display: <?= ($cart_count > 0) ? 'inline-block' : 'none' ?>;">
                            <?= (int) $cart_count ?>
                        </span>
                    </a>
                </li>
                <li class="nav-item ms-lg-2 d-none d-lg-block">
                    <button class="btn btn-outline-light btn-sm rounded-circle theme-toggle-btn p-0 d-flex align-items-center justify-content-center" type="button" aria-label="Ganti Tema" title="Ganti Mode Gelap/Terang" style="width: 38px; height: 38px;">
                        <i class="bi bi-moon-stars-fill theme-icon-moon"></i>
                        <i class="bi bi-sun-fill theme-icon-sun d-none"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="container">
        <?= \App\Helpers\FlashHelper::render() ?>
    </div>
