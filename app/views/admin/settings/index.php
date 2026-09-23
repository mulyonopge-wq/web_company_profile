<?php
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Pengaturan Website & Halaman</h3>
        <p class="text-secondary small mb-0">Kelola metadata SEO, sosial media, footer, dan konten halaman statis.</p>
    </div>
</div>

<div class="row g-4">
    <!-- General & Social Media Form -->
    <div class="col-lg-6">
        <form action="<?= UrlHelper::base('admin/settings/update') ?>" method="POST">
            <?= CsrfHelper::field() ?>

            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-globe me-2 text-primary"></i>Metadata & SEO Global</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Website (Title Tag) *</label>
                    <input type="text" name="site_name" class="form-control" value="<?= e($settings['site_name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Tagline Website</label>
                    <input type="text" name="site_tagline" class="form-control" value="<?= e($settings['site_tagline'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Default Website (Meta Description)</label>
                    <textarea name="site_description" rows="3" class="form-control"><?= e($settings['site_description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Kata Kunci Default (Meta Keywords)</label>
                    <input type="text" name="site_keywords" class="form-control" value="<?= e($settings['site_keywords'] ?? '') ?>">
                </div>
            </div>

            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-share me-2 text-primary"></i>Tautan Media Sosial</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-facebook text-primary me-1"></i> Facebook URL</label>
                    <input type="url" name="social_facebook" class="form-control" value="<?= e($settings['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-instagram text-danger me-1"></i> Instagram URL</label>
                    <input type="url" name="social_instagram" class="form-control" value="<?= e($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-tiktok text-dark me-1"></i> TikTok URL</label>
                    <input type="url" name="social_tiktok" class="form-control" value="<?= e($settings['social_tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@...">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-youtube text-danger me-1"></i> YouTube URL</label>
                    <input type="url" name="social_youtube" class="form-control" value="<?= e($settings['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/@...">
                </div>
            </div>

            <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-layout-text-window-reverse me-2 text-primary"></i>Teks Footer & Hak Cipta</h5>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Deskripsi Singkat Footer</label>
                    <textarea name="footer_text" rows="2" class="form-control"><?= e($settings['footer_text'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Teks Hak Cipta (Copyright)</label>
                    <input type="text" name="footer_copyright" class="form-control" value="<?= e($settings['footer_copyright'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill px-4 mb-4">
                <i class="bi bi-floppy-fill me-1"></i> Simpan Pengaturan
            </button>
        </form>
    </div>

    <!-- CMS Static Pages -->
    <div class="col-lg-6" id="pages">
        <div class="card border rounded-4 p-4 shadow-sm bg-white mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-file-earmark-richtext me-2 text-primary"></i>Kelola Konten Halaman Statis</h5>
            <p class="text-secondary small mb-4">Ubah isi teks dan meta SEO untuk FAQ, Kebijakan Privasi, dan Syarat Ketentuan.</p>

            <div class="accordion" id="pagesAccordion">
                <?php foreach ($pages as $idx => $p): ?>
                    <div class="accordion-item border rounded-3 mb-3 overflow-hidden">
                        <h2 class="accordion-header" id="heading-<?= $idx ?>">
                            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $idx ?>" aria-expanded="false" aria-controls="collapse-<?= $idx ?>">
                                <?= e($p['title']) ?> <span class="badge text-bg-light border ms-2 small text-secondary">/<?= e($p['slug']) ?></span>
                            </button>
                        </h2>
                        <div id="collapse-<?= $idx ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?= $idx ?>" data-bs-parent="#pagesAccordion">
                            <div class="accordion-body bg-light">
                                <form action="<?= UrlHelper::base('admin/settings/page') ?>" method="POST">
                                    <?= CsrfHelper::field() ?>
                                    <input type="hidden" name="slug" value="<?= e($p['slug']) ?>">

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control form-control-sm" value="<?= e($p['meta_title'] ?? '') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Meta Description</label>
                                        <textarea name="meta_description" rows="2" class="form-control form-control-sm"><?= e($p['meta_description'] ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Isi Konten Halaman (Mendukung HTML)</label>
                                        <textarea name="content" rows="8" class="form-control form-control-sm"><?= e($p['content'] ?? '') ?></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                        <i class="bi bi-check-lg me-1"></i> Simpan Halaman
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
