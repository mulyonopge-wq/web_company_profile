<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Order;

class DashboardController extends AdminBaseController
{
    public function index(): void
    {
        $stats = Order::getStatistics();

        $this->renderAdminView('admin/dashboard', [
            'title' => 'Dashboard Overview',
            'stats' => $stats,
        ]);
    }
}
