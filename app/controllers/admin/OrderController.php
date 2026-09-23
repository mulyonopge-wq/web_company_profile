<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use App\Models\Order;

class OrderController extends AdminBaseController
{
    public function index(): void
    {
        $status = $_GET['status'] ?? null;
        $orders = Order::all($status);

        $this->renderAdminView('admin/orders/index', [
            'title' => 'Kelola Pesanan Masuk',
            'orders' => $orders,
            'currentStatus' => $status,
        ]);
    }

    public function detail(string|int $id): void
    {
        $order = Order::findById((int) $id);
        if (!$order) {
            FlashHelper::error('Pesanan tidak ditemukan.');
            UrlHelper::redirect('/admin/orders');
            return;
        }

        $items = Order::getItems((int) $id);

        $this->renderAdminView('admin/orders/detail', [
            'title' => 'Detail Pesanan: ' . $order['order_number'],
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function updateStatus(string|int $id): void
    {
        $status = trim($_POST['status'] ?? '');
        $updated = Order::updateStatus((int) $id, $status);

        if ($updated) {
            FlashHelper::success("Status pesanan berhasil diperbarui menjadi: " . strtoupper($status));
        } else {
            FlashHelper::error('Gagal memperbarui status pesanan.');
        }

        UrlHelper::redirect('/admin/orders/detail/' . $id);
    }

    public function cancel(string|int $id): void
    {
        $order = Order::findById((int) $id);
        if (!$order) {
            FlashHelper::error('Pesanan tidak ditemukan.');
            UrlHelper::redirect('/admin/orders');
            return;
        }

        $cancelled = Order::cancel((int) $id);
        if ($cancelled) {
            FlashHelper::success("Pesanan #{$order['order_number']} berhasil dibatalkan.");
        } else {
            FlashHelper::error('Gagal membatalkan pesanan.');
        }

        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        if (str_contains($ref, '/admin/orders/detail/')) {
            UrlHelper::redirect('/admin/orders/detail/' . $id);
        } else {
            UrlHelper::redirect('/admin/orders');
        }
    }

    public function delete(string|int $id): void
    {
        $order = Order::findById((int) $id);
        if (!$order) {
            FlashHelper::error('Pesanan tidak ditemukan.');
            UrlHelper::redirect('/admin/orders');
            return;
        }

        $deleted = Order::deleteOrder((int) $id);
        if ($deleted) {
            FlashHelper::success("Pesanan #{$order['order_number']} berhasil dihapus permanen.");
        } else {
            FlashHelper::error('Gagal menghapus pesanan.');
        }

        UrlHelper::redirect('/admin/orders');
    }
}
