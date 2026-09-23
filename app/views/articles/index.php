<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Models\Setting;

$badge = $articleBadge ?? (class_exists('App\Models\Setting') ? Setting::get('article_page_badge', 'Pusat Edukasi & Berita') : 'Pusat Edukasi & Berita');
$pageTitle = $articleTitle ?? (class_exists('App\Models\Setting') ? Setting::get('article_page_title', 'Artikel & Tips Teknologi Jaringan') : 'Artikel & Tips Teknologi Jaringan');
$pageSubtitle = $articleSubtitle ?? (class_exists('App\Models\Setting') ? Setting::get('article_page_subtitle', 'Dapatkan wawasan seputar konfigurasi router, optimasi bandwidth kantor, keamanan jaringan, dan ulasan perangkat IT terbaru.') : 'Dapatkan wawasan seputar konfigurasi router, optimasi bandwidth kantor, keamanan jaringan, dan ulasan perangkat IT terbaru.');
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Artikel</li>
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

    <?php if (empty($articles)): ?>
        <div class="card border rounded-4 p-5 text-center my-4">
            <i class="bi bi-journal-text text-secondary display-4 mb-3"></i>
            <h5 class="fw-bold text-dark">Belum Ada Artikel</h5>
            <p class="text-secondary small">Artikel terbaru akan segera hadir.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($articles as $art):
                $articleUrl = UrlHelper::base('artikel/' . $art['slug']);
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 border rounded-4 overflow-hidden shadow-sm">
                        <div style="height: 200px; background-color: #f1f5f9; overflow: hidden;">
                            <a href="<?= $articleUrl ?>">
                                <?php if (!empty($art['thumbnail'])): ?>
                                    <img src="<?= UrlHelper::upload($art['thumbnail']) ?>" alt="<?= e($art['title']) ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-newspaper fs-1"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex align-items-center gap-2 small text-muted mb-2">
                                <span class="badge text-bg-light border text-primary"><?= e($art['category']) ?></span>
                                <span>&bull;</span>
                                <span><?= date('d M Y', strtotime($art['published_at'] ?? $art['created_at'])) ?></span>
                            </div>
                            <h5 class="card-title fw-bold mb-3">
                                <a href="<?= $articleUrl ?>" class="text-dark text-decoration-none">
                                    <?= e($art['title']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-secondary small mb-4">
                                <?= e(Sanitizer::truncate(strip_tags($art['content']), 120)) ?>
                            </p>
                            <a href="<?= $articleUrl ?>" class="btn btn-outline-primary btn-sm rounded-pill mt-auto align-self-start px-3">
                                Baca Artikel <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
