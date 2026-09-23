<?php
use App\Helpers\Sanitizer;
use App\Helpers\SeoHelper;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyName = !empty($settings['site_name']) ? $settings['site_name'] : (!empty($settings['company_name']) ? $settings['company_name'] : 'Perusahaan Kami');
$companyWa = $settings['company_whatsapp'] ?? '081234567890';
?>

<!-- Schema.org Article -->
<?= SeoHelper::renderArticleSchema($article, $settings) ?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base('artikel') ?>">Artikel</a></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px;"><?= e($article['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-5">
        <!-- Main Article Content -->
        <div class="col-lg-8">
            <div class="mb-4">
                <span class="badge text-bg-primary px-3 py-2 rounded-pill mb-2"><?= e($article['category']) ?></span>
                <h1 class="fw-bold text-dark mb-3"><?= e($article['title']) ?></h1>
                <div class="d-flex align-items-center gap-3 text-muted small">
                    <span><i class="bi bi-person-circle me-1"></i> <?= e($companyName) ?></span>
                    <span>&bull;</span>
                    <span><i class="bi bi-calendar3 me-1"></i> <?= date('d F Y', strtotime($article['published_at'] ?? $article['created_at'])) ?></span>
                </div>
            </div>

            <?php if (!empty($article['thumbnail'])): ?>
                <div class="rounded-4 overflow-hidden shadow-sm mb-4" style="max-height: 420px;">
                    <img src="<?= UrlHelper::upload($article['thumbnail']) ?>" alt="<?= e($article['title']) ?>" class="w-100 object-fit-cover">
                </div>
            <?php endif; ?>

            <!-- Article Body -->
            <div class="card border-0 bg-transparent mb-5">
                <div class="article-content leading-relaxed fs-6 text-dark">
                    <?= $article['content'] ?>
                </div>
            </div>

            <!-- Share & WhatsApp Banner -->
            <div class="p-4 rounded-4 bg-light border d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h6 class="fw-bold mb-1">Tertarik dengan solusi jaringan ini?</h6>
                    <small class="text-secondary">Konsultasikan kebutuhan instalasi kantor Anda bersama tim kami.</small>
                </div>
                <a href="<?= WhatsAppHelper::getContactLink($companyWa, "Halo Admin, saya telah membaca artikel '{$article['title']}' dan ingin konsultasi lebih lanjut.") ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-whatsapp me-1"></i> Konsultasi via WhatsApp
                </a>
            </div>
        </div>

        <!-- Sidebar Recent Articles -->
        <div class="col-lg-4">
            <div class="card border rounded-4 p-4 shadow-sm mb-4">
                <h5 class="fw-bold mb-3 text-dark">Artikel Terkait Lainnya</h5>
                <div class="d-flex flex-column gap-3">
                    <?php if (empty($recentArticles)): ?>
                        <p class="text-muted small mb-0">Belum ada artikel lainnya.</p>
                    <?php else: ?>
                        <?php foreach ($recentArticles as $r): ?>
                            <div class="d-flex gap-3">
                                <?php if (!empty($r['thumbnail'])): ?>
                                    <img src="<?= UrlHelper::upload($r['thumbnail']) ?>" class="rounded-3" style="width: 70px; height: 60px; object-fit: cover;" alt="<?= e($r['title']) ?>">
                                <?php endif; ?>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;"><?= date('d M Y', strtotime($r['published_at'] ?? $r['created_at'])) ?></small>
                                    <a href="<?= UrlHelper::base('artikel/' . $r['slug']) ?>" class="text-dark fw-semibold small text-decoration-none">
                                        <?= e(Sanitizer::truncate($r['title'], 55)) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Catalog Promo Widget -->
            <div class="card border-0 rounded-4 p-4 text-white shadow-sm" style="background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);">
                <i class="bi bi-router-fill display-5 mb-3 text-warning"></i>
                <h5 class="fw-bold">Butuh Perangkat Jaringan?</h5>
                <p class="small text-white-50 mb-3">Temukan router MikroTik, switch manageable, dan wireless access point berkualitas.</p>
                <a href="<?= UrlHelper::base('produk') ?>" class="btn btn-light btn-sm rounded-pill fw-bold text-primary px-3">Lihat Katalog Produk</a>
            </div>
        </div>
    </div>
</div>
