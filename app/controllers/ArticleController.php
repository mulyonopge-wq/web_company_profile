<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\Models\Setting;

class ArticleController extends BaseController
{
    public function index(): void
    {
        $articles = Article::getPublished();
        $this->renderView('articles/index', [
            'title' => Setting::get('article_page_title', 'Artikel & Tips Teknologi Jaringan'),
            'articles' => $articles,
            'articleBadge' => Setting::get('article_page_badge', 'Pusat Edukasi & Berita'),
            'articleTitle' => Setting::get('article_page_title', 'Artikel & Tips Teknologi Jaringan'),
            'articleSubtitle' => Setting::get('article_page_subtitle', 'Dapatkan wawasan seputar konfigurasi router, optimasi bandwidth kantor, keamanan jaringan, dan ulasan perangkat IT terbaru.'),
        ]);
    }

    public function detail(string $slug): void
    {
        $article = Article::findBySlug($slug);
        if (!$article || $article['status'] !== 'published') {
            http_response_code(404);
            $this->renderView('errors/404', ['title' => 'Artikel Tidak Ditemukan']);
            return;
        }

        $recentArticles = array_filter(Article::getPublished(5), fn($a) => $a['id'] != $article['id']);

        $this->renderView('articles/detail', [
            'title' => $article['title'],
            'article' => $article,
            'recentArticles' => array_slice($recentArticles, 0, 4),
        ]);
    }
}
