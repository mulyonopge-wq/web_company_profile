<?php
use App\Helpers\CsrfHelper;
use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - CMS</title>

    <!-- Anti-FOUT Theme Initializer -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const theme = savedTheme ? savedTheme : 'light';
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= UrlHelper::asset('css/admin.css') ?>">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-4">
                <div class="bg-primary text-white rounded-4 p-3 d-inline-flex align-items-center justify-content-center shadow mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-shield-lock-fill fs-3"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">Administrator Login</h4>
                <p class="text-secondary small">Masuk untuk mengelola website & marketplace</p>
            </div>

            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <?= FlashHelper::render() ?>

                <form action="<?= UrlHelper::base('admin/login/proses') ?>" method="POST">
                    <?= CsrfHelper::field() ?>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Username atau Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-secondary"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Username / email" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-secondary"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-semibold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Panel
                    </button>
                </form>
            </div>

            <div class="text-center mt-4">
                <a href="<?= UrlHelper::base() ?>" class="text-secondary small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Website Utama
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
