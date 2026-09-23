<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;

$companyWa = $settings['company_whatsapp'] ?? '081234567890';
?>

<!-- Breadcrumb -->
<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">FAQ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="text-center mb-5">
        <span class="badge text-bg-primary px-3 py-2 rounded-pill mb-2">Bantuan Pelanggan</span>
        <h1 class="fw-bold text-dark"><?= e($page['title'] ?? 'Pertanyaan Umum (FAQ)') ?></h1>
        <p class="text-secondary mx-auto" style="max-width: 600px;">
            Jawaban lengkap atas pertanyaan yang sering diajukan seputar pemesanan, ketersediaan stok, pengiriman, dan garansi.
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border rounded-4 p-4 p-lg-5 shadow-sm mb-5 leading-relaxed">
                <?= $page['content'] ?? '' ?>
            </div>

            <!-- Need More Help -->
            <div class="p-4 rounded-4 bg-light border text-center">
                <h5 class="fw-bold text-dark mb-2">Punya pertanyaan lain yang belum terjawab?</h5>
                <p class="text-secondary small mb-3">Tim customer care kami siap membantu via WhatsApp pada jam operasional kerja.</p>
                <a href="<?= WhatsAppHelper::getContactLink($companyWa, "Halo Admin, saya ingin menanyakan hal lain seputar layanan dan produk.") ?>" target="_blank" class="btn btn-success rounded-pill px-4">
                    <i class="bi bi-whatsapp me-1"></i> Tanyakan Langsung ke Admin
                </a>
            </div>
        </div>
    </div>
</div>
