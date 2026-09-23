<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Models\Category;
use App\Models\Product;

class ProductController extends BaseController
{
    public function index(): void
    {
        $categorySlug = $_GET['kategori'] ?? null;
        $searchQuery = $_GET['q'] ?? null;
        $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float) $_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float) $_GET['max_price'] : null;
        $sort = $_GET['sort'] ?? 'newest';
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $filters = [
            'q' => $searchQuery,
            'category_slug' => $categorySlug,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'sort' => $sort,
        ];

        $productData = Product::filter($filters, $page, 12);
        $categories = Category::getActiveWithCount();

        $selectedCategory = null;
        if ($categorySlug) {
            $selectedCategory = Category::findBySlug($categorySlug);
        }

        $pageTitle = 'Katalog Produk';
        if ($selectedCategory) {
            $pageTitle = 'Kategori: ' . $selectedCategory['name'];
        } elseif ($searchQuery) {
            $pageTitle = 'Pencarian: "' . htmlspecialchars($searchQuery) . '"';
        }

        $this->renderView('products/index', [
            'title' => $pageTitle,
            'products' => $productData['items'],
            'total' => $productData['total'],
            'page' => $productData['page'],
            'totalPages' => $productData['total_pages'],
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'filters' => $filters,
        ]);
    }

    public function category(string $slug): void
    {
        $_GET['kategori'] = $slug;
        $this->index();
    }

    public function detail(string $slug): void
    {
        $product = Product::findBySlug($slug);
        if (!$product || empty($product['is_active'])) {
            http_response_code(404);
            $this->renderView('errors/404', ['title' => 'Produk Tidak Ditemukan']);
            return;
        }

        $galleryImages = Product::getGalleryImages((int) $product['id']);
        $relatedProducts = Product::getRelated((int) $product['category_id'], (int) $product['id'], 4);

        $this->renderView('products/detail', [
            'title' => $product['name'],
            'product' => $product,
            'galleryImages' => $galleryImages,
            'relatedProducts' => $relatedProducts,
        ]);
    }

    public function searchApi(): void
    {
        $term = $_GET['q'] ?? '';
        if (mb_strlen($term) < 2) {
            $this->jsonResponse(['results' => []]);
            return;
        }

        $products = Product::searchAutocomplete($term, 8);
        $results = [];

        foreach ($products as $p) {
            $effectivePrice = ($p['discount_price'] > 0) ? $p['discount_price'] : $p['price'];
            $results[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'sku' => $p['sku'],
                'category' => $p['category_name'],
                'price_formatted' => Sanitizer::formatRupiah($effectivePrice),
                'url' => UrlHelper::base('produk/' . $p['slug']),
                'image' => UrlHelper::upload($p['main_image'], 'assets/images/no-image.png'),
            ];
        }

        $this->jsonResponse(['results' => $results]);
    }
}
