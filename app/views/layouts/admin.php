<?php
use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;

$companyName = $settings['company_name'] ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Admin Dashboard') ?> - CMS Panel</title>

    <!-- Anti-FOUT Theme Initializer -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme ? savedTheme : (prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= UrlHelper::asset('css/admin.css') ?>">
    <!-- Chart.js for statistics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar-backdrop"></div>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <div class="bg-primary text-white rounded p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <div>
                <h6 class="sidebar-brand-name">ADMIN PANEL</h6>
                <small class="text-secondary" style="font-size: 0.75rem;"><?= e(mb_strimwidth($companyName, 0, 20, '...')) ?></small>
            </div>
        </div>

        <div class="sidebar-nav">
            <div class="sidebar-heading">Utama</div>
            <a href="<?= UrlHelper::base('admin/dashboard') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/dashboard') ?: UrlHelper::isActive('admin', 'active') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="sidebar-heading">Perusahaan & Situs</div>
            <a href="<?= UrlHelper::base('admin/company') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/company') ?>">
                <i class="bi bi-building"></i> Profil Perusahaan
            </a>
            <a href="<?= UrlHelper::base('admin/settings') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/settings') ?>">
                <i class="bi bi-gear-fill"></i> Pengaturan Website
            </a>
            <a href="<?= UrlHelper::base('admin/banners') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/banners') ?>">
                <i class="bi bi-images"></i> Banner / Slider
            </a>

            <div class="sidebar-heading">E-Commerce & Produk</div>
            <a href="<?= UrlHelper::base('admin/categories') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/categories') ?>">
                <i class="bi bi-tags"></i> Kategori Produk
            </a>
            <a href="<?= UrlHelper::base('admin/products') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/products') ?>">
                <i class="bi bi-box-seam"></i> Produk
            </a>
            <a href="<?= UrlHelper::base('admin/orders') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/orders') ?>">
                <i class="bi bi-cart-check"></i> Pesanan Masuk
            </a>
            <a href="<?= UrlHelper::base('admin/customers') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/customers') ?>">
                <i class="bi bi-people"></i> Pelanggan
            </a>

            <div class="sidebar-heading">Konten & Media</div>
            <a href="<?= UrlHelper::base('admin/articles') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/articles') ?>">
                <i class="bi bi-newspaper"></i> Artikel / Berita
            </a>
            <a href="<?= UrlHelper::base('admin/galleries') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/galleries') ?>">
                <i class="bi bi-camera"></i> Galeri Foto
            </a>

            <div class="sidebar-heading">Pengaturan Akun</div>
            <a href="<?= UrlHelper::base('admin/users') ?>" class="sidebar-link <?= UrlHelper::isActive('admin/users') ?>">
                <i class="bi bi-person-badge"></i> Pengguna Admin
            </a>
            <a href="<?= UrlHelper::base('admin/logout') ?>" class="sidebar-link text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggleBtn" class="btn btn-light btn-sm border d-lg-none" type="button">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <a href="<?= UrlHelper::base() ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Buka Website Publik
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary btn-sm rounded-circle theme-toggle-btn p-0 d-flex align-items-center justify-content-center" type="button" aria-label="Ganti Tema" title="Ganti Mode Gelap/Terang" style="width: 34px; height: 34px;">
                    <i class="bi bi-moon-stars-fill theme-icon-moon"></i>
                    <i class="bi bi-sun-fill theme-icon-sun d-none"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle btn-sm d-flex align-items-center gap-2 border" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-6 text-primary"></i>
                        <span class="fw-semibold"><?= e($currentUser['name'] ?? 'Administrator') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><h6 class="dropdown-header">Login sebagai: <?= e($currentUser['username'] ?? 'admin') ?></h6></li>
                        <li><a class="dropdown-item" href="<?= UrlHelper::base('admin/users') ?>"><i class="bi bi-key me-2"></i> Ganti Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?= UrlHelper::base('admin/logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="admin-content">
            <?= FlashHelper::render() ?>
            <?= $content ?? '' ?>
        </div>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Admin Custom JS -->
<script src="<?= UrlHelper::asset('js/admin.js') ?>"></script>

</body>
</html>
