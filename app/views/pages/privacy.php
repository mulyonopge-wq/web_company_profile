<?php
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="bg-light py-3 border-bottom mb-4">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= UrlHelper::base() ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kebijakan Privasi</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <h1 class="fw-bold text-dark mb-4"><?= e($page['title'] ?? 'Kebijakan Privasi') ?></h1>
            <div class="card border rounded-4 p-4 p-lg-5 shadow-sm leading-relaxed text-secondary">
                <?= $page['content'] ?? '' ?>
            </div>
        </div>
    </div>
</div>
