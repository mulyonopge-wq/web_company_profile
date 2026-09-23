<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Helpers\WhatsAppHelper;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Exception;

class CartController extends BaseController
{
    private function getCart(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return $_SESSION['cart'] ?? [];
    }

    private function saveCart(array $cart): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['cart'] = $cart;
    }

    public function index(): void
    {
        $cart = $this->getCart();
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $this->renderView('cart/index', [
            'title' => 'Keranjang Belanja',
            'cart' => $cart,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
        $isBuyNow = !empty($_POST['buy_now']);

        $product = Product::findById($productId);
        if (!$product || empty($product['is_active'])) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Produk tidak ditemukan atau tidak aktif.'], 404);
            }
            FlashHelper::error('Produk tidak ditemukan.');
            UrlHelper::redirect('/produk');
            return;
        }

        if ($product['stock'] < 1) {
            if ($this->isAjax()) {
                $this->jsonResponse(['success' => false, 'message' => 'Stok produk ini sedang habis.'], 400);
            }
            FlashHelper::warning('Mohon maaf, stok produk ini sedang habis.');
            UrlHelper::redirect('/produk/' . $product['slug']);
            return;
        }

        $cart = $this->getCart();
        $effectivePrice = ($product['discount_price'] > 0) ? (float) $product['discount_price'] : (float) $product['price'];

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;
            if ($newQty > $product['stock']) {
                $newQty = $product['stock'];
            }
            $cart[$productId]['quantity'] = $newQty;
        } else {
            $cart[$productId] = [
                'product_id' => $product['id'],
                'name' => $product['name'],
                'sku' => $product['sku'],
                'slug' => $product['slug'],
                'price' => $effectivePrice,
                'weight' => (int) $product['weight'],
                'image' => $product['main_image'],
                'quantity' => min($quantity, $product['stock']),
            ];
        }

        $this->saveCart($cart);

        if ($this->isAjax()) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang.',
                'cart_count' => $this->getCartCount(),
            ]);
            return;
        }

        FlashHelper::success("Produk \"{$product['name']}\" berhasil ditambahkan ke keranjang.");

        if ($isBuyNow) {
            UrlHelper::redirect('/checkout');
        } else {
            UrlHelper::redirect('/keranjang');
        }
    }

    public function update(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            $product = Product::findById($productId);
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                if ($product && $quantity > $product['stock']) {
                    $quantity = $product['stock'];
                }
                $cart[$productId]['quantity'] = $quantity;
            }
            $this->saveCart($cart);
        }

        if ($this->isAjax()) {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $itemSubtotal = isset($cart[$productId]) ? $cart[$productId]['price'] * $cart[$productId]['quantity'] : 0;

            $this->jsonResponse([
                'success' => true,
                'cart_count' => $this->getCartCount(),
                'item_subtotal_formatted' => Sanitizer::formatRupiah($itemSubtotal),
                'subtotal_formatted' => Sanitizer::formatRupiah($subtotal),
            ]);
            return;
        }

        UrlHelper::redirect('/keranjang');
    }

    public function remove(string|int $id): void
    {
        $productId = (int) $id;
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $this->saveCart($cart);
            FlashHelper::info('Produk telah dihapus dari keranjang.');
        }

        UrlHelper::redirect('/keranjang');
    }

    public function clear(): void
    {
        $this->saveCart([]);
        FlashHelper::info('Keranjang belanja telah dikosongkan.');
        UrlHelper::redirect('/keranjang');
    }

    public function checkout(): void
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            FlashHelper::warning('Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
            UrlHelper::redirect('/produk');
            return;
        }

        $subtotal = 0;
        $totalWeight = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalWeight += ($item['weight'] ?? 500) * $item['quantity'];
        }

        $this->renderView('cart/checkout', [
            'title' => 'Checkout Pesanan',
            'cart' => $cart,
            'subtotal' => $subtotal,
            'totalWeight' => $totalWeight,
        ]);
    }

    public function processCheckout(): void
    {
        $cart = $this->getCart();
        if (empty($cart)) {
            FlashHelper::warning('Keranjang belanja Anda kosong.');
            UrlHelper::redirect('/produk');
            return;
        }

        $name = trim($_POST['customer_name'] ?? '');
        $phone = trim($_POST['customer_phone'] ?? '');
        $email = trim($_POST['customer_email'] ?? '');
        $address = trim($_POST['customer_address'] ?? '');
        $notes = trim($_POST['notes'] ?? '');

        if (empty($name) || empty($phone) || empty($address)) {
            FlashHelper::error('Mohon lengkapi Nama, Nomor WhatsApp, dan Alamat Pengiriman.');
            UrlHelper::redirect('/checkout');
            return;
        }

        $subtotal = 0;
        $items = [];
        foreach ($cart as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $subtotal += $lineTotal;
            $items[] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $lineTotal,
            ];
        }

        $orderData = [
            'customer_name' => $name,
            'customer_phone' => $phone,
            'customer_email' => $email ?: null,
            'customer_address' => $address,
            'notes' => $notes ?: null,
            'subtotal' => $subtotal,
            'total_amount' => $subtotal,
        ];

        try {
            $orderId = Order::createOrder($orderData, $items);
            $order = Order::findById($orderId);

            // Empty cart
            $this->saveCart([]);

            // Generate WhatsApp link
            $adminPhone = Setting::get('company_whatsapp', '081234567890');
            $waLink = WhatsAppHelper::getCheckoutLink($adminPhone, $order, $items);

            // Redirect to WhatsApp
            header("Location: {$waLink}");
            exit;
        } catch (Exception $e) {
            FlashHelper::error('Terjadi kendala saat memproses pesanan Anda: ' . $e->getMessage());
            UrlHelper::redirect('/checkout');
        }
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
