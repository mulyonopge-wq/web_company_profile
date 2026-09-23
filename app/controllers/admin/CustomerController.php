<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Customer;

class CustomerController extends AdminBaseController
{
    public function index(): void
    {
        $customers = Customer::all();
        $this->renderAdminView('admin/customers/index', [
            'title' => 'Daftar Pelanggan',
            'customers' => $customers,
        ]);
    }
}
