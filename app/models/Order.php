<?php
declare(strict_types=1);

namespace App\Models;

use Exception;

class Order extends BaseModel
{
    public static function generateOrderNumber(): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $latest = self::fetch(
            "SELECT `order_number` FROM `orders` WHERE `order_number` LIKE :prefix ORDER BY `id` DESC LIMIT 1",
            [':prefix' => $prefix . '%']
        );

        if ($latest) {
            $lastNum = (int) substr($latest['order_number'], -3);
            $newNum = str_pad((string) ($lastNum + 1), 3, '0', STR_PAD_LEFT);
        } else {
            $newNum = '001';
        }

        return $prefix . $newNum;
    }

    public static function createOrder(array $orderData, array $items): int
    {
        $db = self::db();
        $db->beginTransaction();

        try {
            // Check customer or create
            $customerId = Customer::findOrCreate(
                $orderData['customer_name'],
                $orderData['customer_phone'],
                $orderData['customer_email'] ?? null,
                $orderData['customer_address']
            );

            $orderData['customer_id'] = $customerId;
            if (empty($orderData['order_number'])) {
                $orderData['order_number'] = self::generateOrderNumber();
            }

            $orderId = self::insert('orders', [
                'order_number' => $orderData['order_number'],
                'customer_id' => $customerId,
                'customer_name' => $orderData['customer_name'],
                'customer_phone' => $orderData['customer_phone'],
                'customer_email' => $orderData['customer_email'] ?? null,
                'customer_address' => $orderData['customer_address'],
                'notes' => $orderData['notes'] ?? null,
                'subtotal' => $orderData['subtotal'],
                'total_amount' => $orderData['total_amount'],
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                self::insert('order_items', [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Reduce stock if product exists
                if (!empty($item['product_id'])) {
                    self::query(
                        'UPDATE `products` SET `stock` = GREATEST(0, `stock` - :qty) WHERE `id` = :pid',
                        [':qty' => $item['quantity'], ':pid' => $item['product_id']]
                    );
                }
            }

            $db->commit();
            return $orderId;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function all(?string $status = null): array
    {
        $sql = 'SELECT o.*, COUNT(oi.id) as item_count
                FROM `orders` o
                LEFT JOIN `order_items` oi ON oi.order_id = o.id';
        $params = [];

        if ($status && $status !== 'all') {
            $sql .= ' WHERE o.status = :status';
            $params[':status'] = $status;
        }

        $sql .= ' GROUP BY o.id ORDER BY o.id DESC';
        return self::fetchAll($sql, $params);
    }

    public static function findById(int $id): ?array
    {
        return self::fetch('SELECT * FROM `orders` WHERE `id` = :id LIMIT 1', [':id' => $id]);
    }

    public static function findByOrderNumber(string $orderNumber): ?array
    {
        return self::fetch('SELECT * FROM `orders` WHERE `order_number` = :num LIMIT 1', [':num' => $orderNumber]);
    }

    public static function getItems(int $orderId): array
    {
        return self::fetchAll(
            'SELECT oi.*, p.slug as product_slug, p.main_image
             FROM `order_items` oi
             LEFT JOIN `products` p ON p.id = oi.product_id
             WHERE oi.order_id = :oid
             ORDER BY oi.id ASC',
            [':oid' => $orderId]
        );
    }

    public static function updateStatus(int $id, string $status): int
    {
        $allowed = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return 0;
        }
        return self::update('orders', ['status' => $status], '`id` = :id', [':id' => $id]);
    }

    public static function cancel(int $id): bool
    {
        return self::update('orders', ['status' => 'cancelled'], '`id` = :id', [':id' => $id]) > 0;
    }

    public static function deleteOrder(int $id): bool
    {
        // Delete related items first to ensure clean cascade across MySQL engines
        self::delete('order_items', '`order_id` = :id', [':id' => $id]);
        return self::delete('orders', '`id` = :id', [':id' => $id]) > 0;
    }

    public static function getStatistics(): array
    {
        $totalProducts = (int) self::fetchColumn('SELECT COUNT(*) FROM `products`');
        $activeProducts = (int) self::fetchColumn('SELECT COUNT(*) FROM `products` WHERE `is_active` = 1');
        $outOfStock = (int) self::fetchColumn('SELECT COUNT(*) FROM `products` WHERE `stock` <= 0');

        $totalOrders = (int) self::fetchColumn('SELECT COUNT(*) FROM `orders`');
        $pendingOrders = (int) self::fetchColumn("SELECT COUNT(*) FROM `orders` WHERE `status` = 'pending'");
        $processingOrders = (int) self::fetchColumn("SELECT COUNT(*) FROM `orders` WHERE `status` = 'processing'");
        $completedOrders = (int) self::fetchColumn("SELECT COUNT(*) FROM `orders` WHERE `status` = 'completed'");

        $totalCustomers = (int) self::fetchColumn('SELECT COUNT(*) FROM `customers`');
        $totalArticles = (int) self::fetchColumn('SELECT COUNT(*) FROM `articles`');

        $totalRevenue = (float) (self::fetchColumn("SELECT SUM(total_amount) FROM `orders` WHERE `status` IN ('processing', 'shipped', 'completed')") ?: 0);

        // Recent 5 orders
        $recentOrders = self::fetchAll('SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 5');

        // Monthly sales for Chart.js (past 6 months)
        $monthlyChart = self::fetchAll("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as ym,
                   DATE_FORMAT(created_at, '%b %Y') as label,
                   SUM(total_amount) as total,
                   COUNT(id) as count
            FROM `orders`
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY ym, label
            ORDER BY ym ASC
        ");

        return [
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'out_of_stock' => $outOfStock,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'processing_orders' => $processingOrders,
            'completed_orders' => $completedOrders,
            'total_customers' => $totalCustomers,
            'total_articles' => $totalArticles,
            'total_revenue' => $totalRevenue,
            'recent_orders' => $recentOrders,
            'monthly_chart' => $monthlyChart,
        ];
    }
}
