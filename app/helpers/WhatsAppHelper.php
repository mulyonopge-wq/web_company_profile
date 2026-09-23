<?php
declare(strict_types=1);

namespace App\Helpers;

class WhatsAppHelper
{
    /**
     * Normalize Indonesian phone numbers to 628xxx format
     */
    public static function normalizeNumber(string $number): string
    {
        // Remove spaces, dashes, parentheses, plus
        $clean = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62' . $clean;
        }

        return $clean;
    }

    /**
     * Build standard WhatsApp URL
     */
    public static function createLink(string $phone, string $message = ''): string
    {
        $normalizedPhone = self::normalizeNumber($phone);
        $encodedMessage = urlencode($message);

        return "https://api.whatsapp.com/send?phone={$normalizedPhone}&text={$encodedMessage}";
    }

    /**
     * Generate product inquiry message
     */
    public static function getProductInquiryLink(string $adminPhone, string $productName, float|int $price, string $productUrl): string
    {
        $formattedPrice = Sanitizer::formatRupiah($price);
        $message = "Halo Admin, saya tertarik dengan produk:\n\n" .
                   "{$productName}\n\n" .
                   "Harga: {$formattedPrice}\n\n" .
                   "Link:\n{$productUrl}";

        return self::createLink($adminPhone, $message);
    }

    /**
     * Generate checkout order WhatsApp message
     */
    public static function getCheckoutLink(string $adminPhone, array $order, array $items): string
    {
        $orderNumber = $order['order_number'] ?? 'INV-' . date('Ymd') . '-000';
        $customerName = $order['customer_name'] ?? '';
        $customerPhone = $order['customer_phone'] ?? '';
        $customerAddress = $order['customer_address'] ?? '';
        $totalFormatted = Sanitizer::formatRupiah($order['total_amount'] ?? 0);

        $productListText = "";
        foreach ($items as $item) {
            $itemPrice = Sanitizer::formatRupiah($item['price']);
            $productListText .= "- {$item['product_name']}\n  Qty: {$item['quantity']}\n  Harga: {$itemPrice}\n\n";
        }

        $message = "Halo Admin,\n\n" .
                   "Saya ingin melakukan pemesanan:\n\n" .
                   "Order: {$orderNumber}\n\n" .
                   "Produk:\n" .
                   $productListText .
                   "Total: {$totalFormatted}\n\n" .
                   "Nama: {$customerName}\n" .
                   "Nomor WhatsApp: {$customerPhone}\n" .
                   "Alamat: {$customerAddress}\n\n" .
                   "Terima kasih.";

        return self::createLink($adminPhone, $message);
    }

    /**
     * General contact or consultation link
     */
    public static function getContactLink(string $adminPhone, ?string $customMsg = null): string
    {
        $message = $customMsg ?? "Halo Admin, saya ingin menanyakan informasi seputar layanan dan produk perusahaan.";
        return self::createLink($adminPhone, $message);
    }
}
