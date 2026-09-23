<?php
use App\Helpers\AuthHelper;
use App\Helpers\CsrfHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;

$currentId = AuthHelper::id();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Pengguna / Administrator</h3>
        <p class="text-secondary small mb-0">Kelola akun yang memiliki akses ke Admin Panel.</p>
    </div>
    <div>
        <a href="<?= UrlHelper::base('admin/users/create') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> Tambah Admin Baru
        </a>
    </div>
</div>

<div class="card border rounded-4 shadow-sm bg-white overflow-hidden" style="max-width: 800px;">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light small text-uppercase">
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width: 140px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-dark"><?= e($u['name']) ?></div>
                            <?php if ($u['id'] == $currentId): ?>
                                <span class="badge text-bg-info text-dark" style="font-size: 0.7rem;">Akun Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><code><?= e($u['username']) ?></code></td>
                        <td class="small text-secondary"><?= e($u['email']) ?></td>
                        <td>
                            <span class="badge text-bg-primary rounded-pill"><?= e($u['role']) ?></span>
                        </td>
                        <td class="text-end">
                            <a href="<?= UrlHelper::base('admin/users/edit/' . $u['id']) ?>" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <?php if ($u['id'] != $currentId): ?>
                                <form action="<?= UrlHelper::base('admin/users/delete/' . $u['id']) ?>" method="POST" class="d-inline">
                                    <?= CsrfHelper::field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm-delete="Hapus akun administrator ini?" title="Hapus">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
