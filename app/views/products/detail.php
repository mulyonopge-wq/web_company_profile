<?php
use App\Helpers\Sanitizer;
use App\Helpers\SeoHelper;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyWa = $settings['company_whatsapp'] ?? '081234567890';
$effectivePrice = ($product['discount_price'] > 0) ? $product['discount_price'] : $product['price'];
$hasDiscount = ($product['discount_price'] > 0);
$discountPercent = $hasDiscount ? round((($product['price'] - $product['discount_price']) / $product['price']) * 100) : 0;
$productUrl = UrlHelper::base('produk/' . $product['slug']);

// Siapkan daftar seluruh media produk (gambar & video) untuk preview & lightbox
$productMediaList = [];
if (!empty($product['main_image'])) {
    $productMediaList[] = [
        'type' => 'image',
        'url' => UrlHelper::upload($product['main_image']),
        'thumb' => UrlHelper::upload($product['main_image']),
        'title' => $product['name']
    ];
}
if (!empty($galleryImages)) {
    foreach ($galleryImages as $gImg) {
        if (!empty($gImg['image_path'])) {
            $productMediaList[] = [
                'type' => 'image',
                'url' => UrlHelper::upload($gImg['image_path']),
                'thumb' => UrlHelper::upload($gImg['image_path']),
                'title' => $product['name']
            ];
        }
    }
}
if (empty($productMediaList)) {
    $productMediaList[] = [
        'type' => 'image',
        'url' => UrlHelper::upload($product['main_image'], 'assets/images/no-image.png'),
        'thumb' => UrlHelper::upload($product['main_image'], 'assets/images/no-image.png'),
        'title' => $product['name']
    ];
}

$hasVideo = !empty($product['video_url']) || !empty($product['video_file']);
$videoMediaIndex = null;
if (!empty($product['video_file'])) {
    $videoMediaIndex = count($productMediaList);
    $productMediaList[] = [
        'type' => 'video_file',
        'url' => UrlHelper::upload($product['video_file']),
        'thumb' => !empty($product['main_image']) ? UrlHelper::upload($product['main_image']) : null,
        'title' => 'Video: ' . $product['name']
    ];
}
if (!empty($product['video_url'])) {
    $ytEmbed = UrlHelper::getYoutubeEmbedUrl($product['video_url']);
    $ytThumb = UrlHelper::getYoutubeThumbnailUrl($product['video_url']);
    if ($videoMediaIndex === null) {
        $videoMediaIndex = count($productMediaList);
    }
    $productMediaList[] = [
        'type' => 'youtube',
        'url' => $ytEmbed ?: $product['video_url'],
        'raw_url' => $product['video_url'],
        'thumb' => $ytThumb ?: (!empty($product['main_image']) ? UrlHelper::upload($product['main_image']) : null),
        'title' => 'Video YouTube: ' . $product['name']
    ];
}

$productImagesList = [];
foreach ($productMediaList as $m) {
    if ($m['type'] === 'image') {
        $productImagesList[] = $m['url'];
    }
}
?>

<!-- Schema.org Product -->
<?= SeoHelper::renderProductSchema($product) ?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base('produk') ?>">Produk</a></li>
                <?php if (!empty($product['category_slug'])): ?>
                    <li class="breadcrumb-item"><a href="<?= UrlHelper::base('kategori/' . $product['category_slug']) ?>"><?= e($product['category_name']) ?></a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 250px;"><?= e($product['name']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-5 mb-5">
        <!-- Images & Gallery -->
        <div class="col-lg-5">
            <div class="card border rounded-4 p-3 bg-white shadow-sm mb-3">
                <div class="d-flex align-items-center justify-content-center position-relative product-main-img-box cursor-pointer" style="min-height: 340px; max-height: 400px; overflow: hidden; cursor: zoom-in;" onclick="openProductLightbox()" title="Klik untuk memperbesar foto & video">
                    <img id="mainProductView" src="<?= $productMediaList[0]['url'] ?>" alt="<?= e($product['name']) ?>" class="img-fluid object-fit-contain transition-all" style="max-height: 380px;">
                    <?php if ($hasDiscount): ?>
                        <span class="product-badge-discount">HEMAT <?= $discountPercent ?>%</span>
                    <?php endif; ?>
                    <button type="button" class="btn btn-sm btn-light border shadow-sm position-absolute bottom-0 end-0 m-3 rounded-pill px-3 py-1 d-flex align-items-center gap-1 opacity-90" style="font-size: 0.8rem; pointer-events: none;">
                        <i class="bi bi-arrows-fullscreen text-primary"></i> <span class="fw-semibold">Perbesar</span>
                    </button>
                </div>
            </div>

            <!-- Thumbnail Carousel / Grid -->
            <?php if (count($productMediaList) > 1): ?>
                <div class="d-flex gap-2 overflow-x-auto pb-2">
                    <?php foreach ($productMediaList as $idx => $mItem): ?>
                        <?php if ($mItem['type'] === 'image'): ?>
                            <div class="border rounded-3 p-1 cursor-pointer thumb-item <?= $idx === 0 ? 'border-primary active' : '' ?>" data-index="<?= $idx ?>" style="width: 70px; height: 70px; flex-shrink: 0;" onclick="changeMainImage(<?= $idx ?>)">
                                <img src="<?= $mItem['url'] ?>" class="w-100 h-100 object-fit-contain">
                            </div>
                        <?php else: ?>
                            <div class="border border-danger rounded-3 p-1 cursor-pointer thumb-item thumb-video position-relative" data-index="<?= $idx ?>" style="width: 70px; height: 70px; flex-shrink: 0;" onclick="openProductLightbox(<?= $idx ?>)" title="Klik untuk memutar video di popup">
                                <div class="position-relative w-100 h-100 rounded-2 overflow-hidden bg-black">
                                    <?php if (!empty($mItem['thumb'])): ?>
                                        <img src="<?= e($mItem['thumb']) ?>" class="w-100 h-100 object-fit-cover" alt="Video">
                                    <?php else: ?>
                                        <img src="<?= UrlHelper::upload($product['main_image']) ?>" class="w-100 h-100 object-fit-cover opacity-75" alt="Video">
                                    <?php endif; ?>

                                    <!-- Video Overlay & Play Button -->
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-40 d-flex flex-column align-items-center justify-content-center">
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 26px; height: 26px;">
                                            <i class="bi bi-play-fill fs-5" style="margin-left: 2px;"></i>
                                        </div>
                                        <span class="badge bg-black bg-opacity-75 text-white px-1 py-0 mt-1" style="font-size: 0.55rem; letter-spacing: 0.5px;">VIDEO</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Purchase Information -->
        <div class="col-lg-7">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge text-bg-primary"><?= e($product['category_name'] ?? 'Kategori') ?></span>
                <span class="badge text-bg-light text-secondary border">SKU: <?= e($product['sku']) ?></span>
                <?php if ($product['stock'] > 0): ?>
                    <span class="badge text-bg-success"><i class="bi bi-check-circle me-1"></i> Stok Tersedia (<?= (int)$product['stock'] ?>)</span>
                <?php else: ?>
                    <span class="badge text-bg-danger"><i class="bi bi-x-circle me-1"></i> Stok Habis</span>
                <?php endif; ?>
            </div>

            <h1 class="fw-bold text-dark mb-3"><?= e($product['name']) ?></h1>

            <div class="bg-light p-3 rounded-4 mb-4 d-flex align-items-baseline gap-3">
                <span class="display-6 fw-bold text-danger"><?= Sanitizer::formatRupiah($effectivePrice) ?></span>
                <?php if ($hasDiscount): ?>
                    <span class="fs-5 text-muted text-decoration-line-through"><?= Sanitizer::formatRupiah($product['price']) ?></span>
                <?php endif; ?>
            </div>

            <p class="text-secondary leading-relaxed mb-4">
                <?= nl2br(e($product['short_description'])) ?>
            </p>

            <div class="row g-2 mb-4 small text-secondary">
                <div class="col-sm-6">
                    <strong><i class="bi bi-box me-1"></i> Berat:</strong> <?= (int)$product['weight'] ?> gram (<?= round($product['weight']/1000, 2) ?> kg)
                </div>
                <div class="col-sm-6">
                    <strong><i class="bi bi-shield-check me-1"></i> Garansi:</strong> Resmi Distributor
                </div>
            </div>

            <hr class="my-4">

            <!-- Purchase / Cart Form -->
            <form action="<?= UrlHelper::base('keranjang/tambah') ?>" method="POST" class="ajax-add-to-cart mb-3">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <div class="input-group" style="width: 140px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(-1)">-</button>
                        <input type="number" id="detailQtyInput" name="quantity" class="form-control text-center fw-bold" value="1" min="1" max="<?= (int) $product['stock'] ?>">
                        <button class="btn btn-outline-secondary" type="button" onclick="adjustQty(1)">+</button>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 flex-grow-1" <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                        <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                    </button>
                </div>
            </form>

            <!-- Direct Buy Now & WhatsApp Inquiry -->
            <div class="row g-2 mb-4">
                <div class="col-sm-6">
                    <form action="<?= UrlHelper::base('keranjang/tambah') ?>" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="buy_now" value="1">
                        <button type="submit" class="btn btn-danger w-100 rounded-pill py-2 fw-semibold" <?= ($product['stock'] <= 0) ? 'disabled' : '' ?>>
                            <i class="bi bi-lightning-charge-fill me-1"></i> Beli Sekarang
                        </button>
                    </form>
                </div>
                <div class="col-sm-6">
                    <a href="<?= WhatsAppHelper::getProductInquiryLink($companyWa, $product['name'], $effectivePrice, $productUrl) ?>" target="_blank" class="btn btn-success w-100 rounded-pill py-2 fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i> Tanya via WhatsApp
                    </a>
                </div>
            </div>

            <div class="alert alert-info border-0 rounded-4 py-2 px-3 small d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill fs-5"></i>
                <div>
                    Pemesanan langsung diproses cepat oleh sales resmi kami via WhatsApp dengan bukti invoice dan nomor pesanan.
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description & Specifications Tabs -->
    <div class="card border rounded-4 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom pt-3">
            <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-semibold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                        <i class="bi bi-file-text me-1"></i> Deskripsi Lengkap
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-semibold" id="spec-tab" data-bs-toggle="tab" data-bs-target="#spec-pane" type="button" role="tab">
                        <i class="bi bi-cpu me-1"></i> Spesifikasi Teknis
                    </button>
                </li>
                <?php if ($hasVideo): ?>
                    <li class="nav-item">
                        <button class="nav-link fw-semibold text-danger" id="video-tab" data-bs-toggle="tab" data-bs-target="#video-pane" type="button" role="tab">
                            <i class="bi bi-play-circle-fill me-1"></i> Video Produk
                        </button>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="card-body p-4">
            <div class="tab-content" id="productTabContent">
                <div class="tab-pane fade show active leading-relaxed" id="desc-pane" role="tabpanel">
                    <?= !empty($product['full_description']) ? nl2br(e($product['full_description'])) : nl2br(e($product['short_description'])) ?>
                </div>
                <div class="tab-pane fade" id="spec-pane" role="tabpanel">
                    <?php if (!empty($product['specification'])): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped small">
                                <tbody>
                                    <?php
                                    $specLines = explode("\n", $product['specification']);
                                    foreach ($specLines as $line):
                                        if (empty(trim($line))) continue;
                                        if (str_contains($line, ':')):
                                            [$sKey, $sVal] = explode(':', $line, 2);
                                    ?>
                                            <tr>
                                                <th class="bg-light w-25"><?= e(trim($sKey)) ?></th>
                                                <td><?= e(trim($sVal)) ?></td>
                                            </tr>
                                    <?php   else: ?>
                                            <tr>
                                                <td colspan="2"><?= e(trim($line)) ?></td>
                                            </tr>
                                    <?php   endif;
                                    endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Spesifikasi detail tidak tersedia untuk produk ini.</p>
                    <?php endif; ?>
                </div>
                <?php if ($hasVideo): ?>
                    <div class="tab-pane fade" id="video-pane" role="tabpanel">
                        <div class="row justify-content-center py-2">
                            <div class="col-lg-10">
                                <?php if (!empty($product['video_url'])): 
                                    $embedUrl = UrlHelper::getYoutubeEmbedUrl($product['video_url']);
                                ?>
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-youtube text-danger me-2"></i>Video YouTube Produk</h6>
                                        <?php if ($embedUrl): ?>
                                            <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border">
                                                <iframe src="<?= e($embedUrl) ?>" title="Video <?= e($product['name']) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?= e($product['video_url']) ?>" target="_blank" class="btn btn-outline-danger">
                                                <i class="bi bi-youtube me-2"></i> Buka Tautan Video di YouTube
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($product['video_file'])): ?>
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-3"><i class="bi bi-camera-video-fill text-primary me-2"></i>Pemutar Video Produk</h6>
                                        <div class="rounded-4 overflow-hidden shadow-sm border bg-black text-center">
                                            <video class="w-100" style="max-height: 480px;" controls preload="metadata">
                                                <source src="<?= UrlHelper::upload($product['video_file']) ?>">
                                                Browser Anda tidak mendukung pemutar video HTML5.
                                            </video>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-4">Produk Terkait</h4>
            <div class="row g-3">
                <?php foreach ($relatedProducts as $rel):
                    $rEffectivePrice = ($rel['discount_price'] > 0) ? $rel['discount_price'] : $rel['price'];
                    $rUrl = UrlHelper::base('produk/' . $rel['slug']);
                ?>
                    <div class="col-xl-3 col-md-6">
                        <div class="product-card">
                            <div class="product-img-wrap" style="height: 180px;">
                                <a href="<?= $rUrl ?>" class="d-block w-100 h-100 text-center">
                                    <img src="<?= UrlHelper::upload($rel['main_image'], 'assets/images/no-image.png') ?>" alt="<?= e($rel['name']) ?>" loading="lazy">
                                </a>
                            </div>
                            <div class="product-body">
                                <h6 class="product-title small">
                                    <a href="<?= $rUrl ?>"><?= e($rel['name']) ?></a>
                                </h6>
                                <div class="product-price-box mb-2">
                                    <span class="product-price-current fs-6"><?= Sanitizer::formatRupiah($rEffectivePrice) ?></span>
                                </div>
                                <a href="<?= $rUrl ?>" class="btn btn-outline-primary btn-sm rounded-pill w-100">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if ($hasVideo): ?>
<!-- Product Video Popup Modal -->
<div class="modal fade" id="productVideoModal" tabindex="-1" aria-labelledby="productVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg overflow-hidden rounded-4 bg-dark text-white">
            <div class="modal-header border-0 pb-2 pt-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fs-6 fw-bold text-white d-flex align-items-center gap-2 mb-0" id="productVideoModalLabel">
                    <i class="bi bi-play-circle-fill text-danger fs-5"></i>
                    <span>Video: <?= e($product['name']) ?></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-2 p-md-3">
                <?php if (!empty($product['video_file'])): ?>
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-black mb-<?= (!empty($product['video_url']) ? '3' : '0') ?>">
                        <video id="modalProductVideo" class="w-100 h-100" controls preload="auto" playsinline>
                            <source src="<?= UrlHelper::upload($product['video_file']) ?>" type="video/mp4">
                            Browser Anda tidak mendukung pemutar video HTML5.
                        </video>
                    </div>
                <?php endif; ?>

                <?php if (!empty($product['video_url'])): 
                    $modalEmbedUrl = UrlHelper::getYoutubeEmbedUrl($product['video_url']);
                ?>
                    <?php if ($modalEmbedUrl): ?>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden bg-black">
                            <iframe id="modalYoutubeIframe" 
                                    data-src="<?= e($modalEmbedUrl) ?>" 
                                    src="" 
                                    title="Video <?= e($product['name']) ?>" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                            </iframe>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-white">
                            <a href="<?= e($product['video_url']) ?>" target="_blank" class="btn btn-outline-danger">
                                <i class="bi bi-youtube me-2"></i> Buka Tautan Video di YouTube
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-0 pt-0 pb-3 px-3 px-md-4 d-flex justify-content-between align-items-center">
                <small class="text-secondary"><i class="bi bi-info-circle me-1"></i> Klik di luar layar atau tombol Tutup untuk menghentikan video.</small>
                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal Lightbox Pop-up Gambar & Video Produk dengan Tombol Geser / Next -->
<div class="modal fade modal-product-lightbox" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-md-down">
        <div class="modal-content bg-dark border-0 shadow-2xl rounded-4 overflow-hidden position-relative" style="background-color: rgba(15, 20, 28, 0.96) !important; backdrop-filter: blur(12px);">
            <!-- Header Modal -->
            <div class="modal-header border-0 py-3 px-4 d-flex justify-content-between align-items-center bg-transparent">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill font-monospace fs-6">
                        <i id="lightboxTypeIcon" class="bi bi-images me-1"></i> <span id="lightboxCurrentNum">1</span> / <span id="lightboxTotalNum"><?= count($productMediaList) ?></span>
                    </span>
                    <span id="lightboxVideoBadge" class="badge bg-danger px-2 py-1 rounded-pill small d-none">
                        <i class="bi bi-play-circle-fill me-1"></i> VIDEO
                    </span>
                    <h6 class="modal-title fw-bold text-white text-truncate mb-0 ms-2" style="max-width: 420px;" id="productImageModalLabel">
                        <?= e($product['name']) ?>
                    </h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-light btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" data-bs-dismiss="modal" aria-label="Tutup" style="width: 38px; height: 38px;" title="Tutup (Esc)">
                        <i class="bi bi-x-lg fs-6"></i>
                    </button>
                </div>
            </div>

            <!-- Body Modal (Gambar/Video & Tombol Geser Kiri-Kanan) -->
            <div class="modal-body p-0 d-flex align-items-center justify-content-center position-relative" style="min-height: 480px; max-height: 82vh; overflow: hidden; user-select: none;">
                <?php if (count($productMediaList) > 1): ?>
                <!-- Tombol Geser Kiri / Prev -->
                <button type="button" class="btn-lightbox-nav btn-lightbox-prev position-absolute start-0 top-50 translate-middle-y ms-3 z-3 shadow" onclick="prevLightboxMedia()" aria-label="Sebelumnya" title="Sebelumnya (Geser Kiri / Panah Kiri)">
                    <i class="bi bi-chevron-left fs-3"></i>
                </button>
                <?php endif; ?>

                <!-- Kontainer Gambar Pop-up -->
                <div id="lightboxImageContainer" class="w-100 h-100 d-flex align-items-center justify-content-center p-3 text-center">
                    <img id="lightboxImage" src="<?= $productMediaList[0]['url'] ?>" alt="<?= e($product['name']) ?>" class="img-fluid object-fit-contain shadow-sm" style="max-height: 76vh; max-width: 100%; border-radius: 12px; transition: opacity 0.2s ease;">
                </div>

                <!-- Kontainer Video Pop-up -->
                <div id="lightboxVideoContainer" class="w-100 h-100 d-none flex-column align-items-center justify-content-center p-3 text-center" style="max-width: 960px; margin: 0 auto;">
                    <div class="ratio ratio-16x9 w-100 rounded-3 overflow-hidden bg-black shadow-lg">
                        <video id="lightboxVideoPlayer" class="w-100 h-100 d-none" controls playsinline preload="metadata" style="object-fit: contain;"></video>
                        <iframe id="lightboxYoutubePlayer" class="w-100 h-100 d-none" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>
                </div>

                <?php if (count($productMediaList) > 1): ?>
                <!-- Tombol Geser Kanan / Next -->
                <button type="button" class="btn-lightbox-nav btn-lightbox-next position-absolute end-0 top-50 translate-middle-y me-3 z-3 shadow" onclick="nextLightboxMedia()" aria-label="Berikutnya" title="Berikutnya (Geser Kanan / Panah Kanan)">
                    <i class="bi bi-chevron-right fs-3"></i>
                </button>
                <?php endif; ?>
            </div>
            <!-- Slide bar thumbnail dibawah foto dihapus sesuai permintaan -->
        </div>
    </div>
</div>

<style>
.product-main-img-box {
    transition: transform 0.2s ease;
}
.product-main-img-box:hover #mainProductView {
    transform: scale(1.02);
}
.btn-lightbox-nav {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.22);
    border: 1px solid rgba(255, 255, 255, 0.4);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(8px);
    cursor: pointer;
    transition: all 0.25s ease-in-out;
}
.btn-lightbox-nav:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #ffffff;
    transform: translateY(-50%) scale(1.12);
    box-shadow: 0 0 20px rgba(13, 110, 253, 0.6);
}
.btn-lightbox-nav:active {
    transform: translateY(-50%) scale(0.92);
}
@media (max-width: 768px) {
    .btn-lightbox-nav {
        width: 42px;
        height: 42px;
    }
    .btn-lightbox-nav i {
        font-size: 1.25rem !important;
    }
}
</style>

<script>
const productMedia = <?= json_encode($productMediaList) ?>;
let currentMediaIndex = 0;

function changeMainImage(index) {
    if (typeof index === 'string') {
        const found = productMedia.findIndex(m => m.url === index);
        index = found !== -1 ? found : 0;
    }
    if (index < 0 || index >= productMedia.length) return;
    
    currentMediaIndex = index;
    const item = productMedia[currentMediaIndex];
    const mainImg = document.getElementById('mainProductView');
    if (mainImg && item.type === 'image') {
        mainImg.src = item.url;
    }
    
    // Highlight thumbnail aktif di halaman utama
    document.querySelectorAll('.thumb-item').forEach((thumb) => {
        const itemIdx = parseInt(thumb.getAttribute('data-index'));
        if (itemIdx === currentMediaIndex) {
            thumb.classList.add('border-primary', 'active');
        } else {
            thumb.classList.remove('border-primary', 'active');
        }
    });
}

function openProductLightbox(startIndex = null) {
    if (startIndex !== null && startIndex >= 0 && startIndex < productMedia.length) {
        currentMediaIndex = startIndex;
    }
    setLightboxMedia(currentMediaIndex);
    
    const modalEl = document.getElementById('productImageModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }
}

function setLightboxMedia(index) {
    if (!productMedia || productMedia.length === 0) return;
    
    if (index < 0) {
        index = productMedia.length - 1;
    } else if (index >= productMedia.length) {
        index = 0;
    }
    
    currentMediaIndex = index;
    const media = productMedia[currentMediaIndex];
    
    // Update counter & badge
    const currentNumEl = document.getElementById('lightboxCurrentNum');
    if (currentNumEl) {
        currentNumEl.textContent = (currentMediaIndex + 1);
    }
    const iconEl = document.getElementById('lightboxTypeIcon');
    const badgeEl = document.getElementById('lightboxVideoBadge');
    
    const imgContainer = document.getElementById('lightboxImageContainer');
    const vidContainer = document.getElementById('lightboxVideoContainer');
    const lightboxImg = document.getElementById('lightboxImage');
    const videoPlayer = document.getElementById('lightboxVideoPlayer');
    const ytPlayer = document.getElementById('lightboxYoutubePlayer');
    
    // Stop & reset pemutar video yang sedang berjalan
    if (videoPlayer) {
        videoPlayer.pause();
        videoPlayer.currentTime = 0;
        videoPlayer.src = '';
        videoPlayer.classList.add('d-none');
    }
    if (ytPlayer) {
        ytPlayer.src = '';
        ytPlayer.classList.add('d-none');
    }

    if (media.type === 'image') {
        if (iconEl) iconEl.className = 'bi bi-images me-1';
        if (badgeEl) badgeEl.classList.add('d-none');
        if (vidContainer) {
            vidContainer.classList.add('d-none');
            vidContainer.classList.remove('d-flex');
        }
        if (imgContainer) imgContainer.classList.remove('d-none');
        
        if (lightboxImg) {
            lightboxImg.style.opacity = '0.3';
            lightboxImg.src = media.url;
            lightboxImg.onload = () => {
                lightboxImg.style.opacity = '1';
            };
        }
        changeMainImage(currentMediaIndex);
    } else if (media.type === 'video_file') {
        if (iconEl) iconEl.className = 'bi bi-play-circle me-1';
        if (badgeEl) badgeEl.classList.remove('d-none');
        if (imgContainer) imgContainer.classList.add('d-none');
        if (vidContainer) {
            vidContainer.classList.remove('d-none');
            vidContainer.classList.add('d-flex');
        }
        if (videoPlayer) {
            videoPlayer.classList.remove('d-none');
            videoPlayer.src = media.url;
            videoPlayer.currentTime = 0;
            const playPromise = videoPlayer.play();
            if (playPromise !== undefined) {
                playPromise.catch(err => {
                    console.log('Video autoplay prevented by browser policy:', err);
                });
            }
        }
    } else if (media.type === 'youtube') {
        if (iconEl) iconEl.className = 'bi bi-youtube me-1';
        if (badgeEl) badgeEl.classList.remove('d-none');
        if (imgContainer) imgContainer.classList.add('d-none');
        if (vidContainer) {
            vidContainer.classList.remove('d-none');
            vidContainer.classList.add('d-flex');
        }
        if (ytPlayer) {
            ytPlayer.classList.remove('d-none');
            const sep = media.url.includes('?') ? '&' : '?';
            ytPlayer.src = media.url + sep + 'autoplay=1&rel=0';
        }
    }
}

function nextLightboxMedia() {
    setLightboxMedia(currentMediaIndex + 1);
}

function prevLightboxMedia() {
    setLightboxMedia(currentMediaIndex - 1);
}

// Compatibility aliases
function nextLightboxImage() { nextLightboxMedia(); }
function prevLightboxImage() { prevLightboxMedia(); }
function setLightboxImage(idx) { setLightboxMedia(idx); }

function openProductVideoModal() {
    <?php if ($videoMediaIndex !== null): ?>
        openProductLightbox(<?= $videoMediaIndex ?>);
    <?php else: ?>
        openProductLightbox(0);
    <?php endif; ?>
}

// Navigasi keyboard (Panah Kiri & Panah Kanan)
document.addEventListener('keydown', (e) => {
    const modalEl = document.getElementById('productImageModal');
    if (modalEl && modalEl.classList.contains('show')) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            prevLightboxMedia();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextLightboxMedia();
        }
    }
});

// Navigasi swipe layar sentuh di HP/Mobile & cleanup saat tutup modal
document.addEventListener('DOMContentLoaded', () => {
    const lightboxModal = document.getElementById('productImageModal');
    if (lightboxModal) {
        // Otomatis pause/hentikan video saat popup ditutup
        lightboxModal.addEventListener('hidden.bs.modal', () => {
            const videoPlayer = document.getElementById('lightboxVideoPlayer');
            if (videoPlayer) {
                videoPlayer.pause();
                videoPlayer.currentTime = 0;
                videoPlayer.src = '';
            }
            const ytPlayer = document.getElementById('lightboxYoutubePlayer');
            if (ytPlayer) {
                ytPlayer.src = '';
            }
        });

        const lightboxContainer = lightboxModal.querySelector('.modal-body');
        if (lightboxContainer) {
            let touchStartX = 0;
            let touchEndX = 0;
            
            lightboxContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            lightboxContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 45) {
                    // Geser ke kiri -> Media Selanjutnya
                    nextLightboxMedia();
                } else if (touchEndX - touchStartX > 45) {
                    // Geser ke kanan -> Media Sebelumnya
                    prevLightboxMedia();
                }
            }, { passive: true });
        }
    }

    // Pastikan thumbnail preview video lokal menampilkan frame
    document.querySelectorAll('.video-thumb-preview').forEach(v => {
        v.addEventListener('loadedmetadata', () => {
            v.currentTime = 0.5;
        });
    });
});

function adjustQty(amount) {
    const input = document.getElementById('detailQtyInput');
    let val = parseInt(input.value) || 1;
    val = Math.max(1, Math.min(val + amount, parseInt(input.max) || 999));
    input.value = val;
}

function switchToVideoTab(el) {
    const videoTabBtn = document.getElementById('video-tab');
    if (videoTabBtn) {
        const tab = new bootstrap.Tab(videoTabBtn);
        tab.show();
        videoTabBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}
</script>
