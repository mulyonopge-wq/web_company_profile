<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Helpers\AuthHelper;
use App\Helpers\CsrfHelper;
use App\Helpers\UrlHelper;

class AdminBaseController extends BaseController
{
    protected ?array $currentUser = null;

    public function __construct()
    {
        parent::__construct();

        // Enforce admin login
        AuthHelper::requireAuth();
        $this->currentUser = AuthHelper::user();

        // Enforce CSRF on all POST requests in admin area
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CsrfHelper::verifyPost();
        }
    }

    public function renderAdminView(string $viewPath, array $data = []): void
    {
        $data['currentUser'] = $this->currentUser;
        $this->renderView($viewPath, $data, 'layouts/admin');
    }
}
