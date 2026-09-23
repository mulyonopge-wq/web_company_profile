<?php
declare(strict_types=1);

namespace App\Helpers;

class SeoHelper
{
    public static function renderMeta(array $meta = []): string
    {
        $siteName = !empty($meta['site_name']) ? trim($meta['site_name']) : 'Website';
        $siteTagline = !empty($meta['site_tagline']) ? trim($meta['site_tagline']) : '';
        $isHome = !empty($meta['is_home']);

        if ($isHome) {
            if (!empty($siteTagline) && strcasecmp($siteTagline, $siteName) !== 0) {
                $title = "{$siteName} - {$siteTagline}";
            } else {
                $title = $siteName;
            }
        } else {
            $pageTitle = !empty($meta['title']) ? trim($meta['title']) : '';
            $title = !empty($pageTitle) ? "{$pageTitle} | {$siteName}" : $siteName;
        }

        $description = $meta['description'] ?? 'Solusi terpercaya kebutuhan networking, komputer, dan perlengkapan IT perusahaan & perorangan.';
        $keywords = $meta['keywords'] ?? 'toko komputer, networking, mikrotik, router, switch, wifi, it solution';
        $image = $meta['image'] ?? UrlHelper::asset('images/og-default.jpg');
        $url = $meta['url'] ?? UrlHelper::current();
        $type = $meta['type'] ?? 'website';

        $html = "<!-- Primary Meta Tags -->\n";
        $html .= "<title>" . Sanitizer::e($title) . "</title>\n";
        $html .= "<meta name=\"title\" content=\"" . Sanitizer::e($title) . "\">\n";
        $html .= "<meta name=\"description\" content=\"" . Sanitizer::e($description) . "\">\n";
        $html .= "<meta name=\"keywords\" content=\"" . Sanitizer::e($keywords) . "\">\n";
        $html .= "<link rel=\"canonical\" href=\"" . Sanitizer::e($url) . "\">\n";

        $html .= "\n<!-- Open Graph / Facebook -->\n";
        $html .= "<meta property=\"og:type\" content=\"" . Sanitizer::e($type) . "\">\n";
        $html .= "<meta property=\"og:url\" content=\"" . Sanitizer::e($url) . "\">\n";
        $html .= "<meta property=\"og:title\" content=\"" . Sanitizer::e($title) . "\">\n";
        $html .= "<meta property=\"og:description\" content=\"" . Sanitizer::e($description) . "\">\n";
        $html .= "<meta property=\"og:image\" content=\"" . Sanitizer::e($image) . "\">\n";
        $html .= "<meta property=\"og:site_name\" content=\"" . Sanitizer::e($siteName) . "\">\n";

        $html .= "\n<!-- Twitter -->\n";
        $html .= "<meta property=\"twitter:card\" content=\"summary_large_image\">\n";
        $html .= "<meta property=\"twitter:url\" content=\"" . Sanitizer::e($url) . "\">\n";
        $html .= "<meta property=\"twitter:title\" content=\"" . Sanitizer::e($title) . "\">\n";
        $html .= "<meta property=\"twitter:description\" content=\"" . Sanitizer::e($description) . "\">\n";
        $html .= "<meta property=\"twitter:image\" content=\"" . Sanitizer::e($image) . "\">\n";

        return $html;
    }

    public static function renderOrganizationSchema(array $company): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $company['name'] ?? 'Solusi Tekno Nusantara',
            'url' => UrlHelper::base(),
            'logo' => !empty($company['logo']) ? UrlHelper::upload($company['logo']) : UrlHelper::asset('images/logo.png'),
            'description' => $company['description'] ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $company['address'] ?? '',
                'addressCountry' => 'ID',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $company['phone'] ?? '',
                'contactType' => 'customer service',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
        ];

        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }

    public static function renderProductSchema(array $product): string
    {
        $price = !empty($product['discount_price']) && $product['discount_price'] > 0
            ? $product['discount_price']
            : $product['price'];

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'],
            'image' => [UrlHelper::upload($product['main_image'] ?? '')],
            'description' => strip_tags($product['short_description'] ?? $product['name']),
            'sku' => $product['sku'] ?? '',
            'offers' => [
                '@type' => 'Offer',
                'url' => UrlHelper::base('produk/' . ($product['slug'] ?? '')),
                'priceCurrency' => 'IDR',
                'price' => (float) $price,
                'availability' => ($product['stock'] ?? 0) > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ];

        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }

    public static function renderArticleSchema(array $article, array $company = []): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $article['title'],
            'image' => [UrlHelper::upload($article['thumbnail'] ?? '')],
            'datePublished' => date('c', strtotime($article['published_at'] ?? $article['created_at'])),
            'dateModified' => date('c', strtotime($article['updated_at'] ?? $article['created_at'])),
            'author' => [
                '@type' => 'Organization',
                'name' => $company['name'] ?? 'Solusi Tekno Nusantara',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $company['name'] ?? 'Solusi Tekno Nusantara',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => !empty($company['logo']) ? UrlHelper::upload($company['logo']) : UrlHelper::asset('images/logo.png'),
                ],
            ],
            'description' => strip_tags($article['meta_description'] ?? Sanitizer::truncate($article['content'] ?? '', 160)),
        ];

        return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }
}
