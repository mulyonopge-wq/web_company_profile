<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\UrlHelper;
use App\Models\Article;
use App\Models\Category;
use App\Models\Product;

class SeoController extends BaseController
{
    public function robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $sitemapUrl = UrlHelper::base('sitemap.xml');
        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /checkout\n";
        echo "Disallow: /keranjang\n\n";
        echo "Sitemap: {$sitemapUrl}\n";
        exit;
    }

    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        $baseUrl = UrlHelper::base();
        $urls = [];

        // Static routes
        $staticRoutes = [
            '',
            'tentang',
            'produk',
            'artikel',
            'galeri',
            'kontak',
            'faq',
            'kebijakan-privasi',
            'syarat-ketentuan',
        ];

        foreach ($staticRoutes as $route) {
            $urls[] = [
                'loc' => $baseUrl . ($route ? '/' . $route : ''),
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => ($route === '') ? '1.0' : '0.8',
            ];
        }

        // Categories
        $categories = Category::getActiveWithCount();
        foreach ($categories as $cat) {
            $urls[] = [
                'loc' => $baseUrl . '/kategori/' . $cat['slug'],
                'lastmod' => date('Y-m-d', strtotime($cat['updated_at'] ?? 'now')),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        // Products
        $products = Product::fetchAll("SELECT slug, updated_at FROM `products` WHERE `is_active` = 1");
        foreach ($products as $p) {
            $urls[] = [
                'loc' => $baseUrl . '/produk/' . $p['slug'],
                'lastmod' => date('Y-m-d', strtotime($p['updated_at'] ?? 'now')),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ];
        }

        // Articles
        $articles = Article::getPublished();
        foreach ($articles as $a) {
            $urls[] = [
                'loc' => $baseUrl . '/artikel/' . $a['slug'],
                'lastmod' => date('Y-m-d', strtotime($a['updated_at'] ?? 'now')),
                'changefreq' => 'weekly',
                'priority' => '0.6',
            ];
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($u['loc'], ENT_QUOTES, 'UTF-8') . "</loc>\n";
            echo "    <lastmod>{$u['lastmod']}</lastmod>\n";
            echo "    <changefreq>{$u['changefreq']}</changefreq>\n";
            echo "    <priority>{$u['priority']}</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>';
        exit;
    }
}
