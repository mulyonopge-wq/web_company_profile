<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Models\Article;
use App\Models\Setting;

class ArticleController extends AdminBaseController
{
    public function index(): void
    {
        $articles = Article::all();
        $articleBadge = Setting::get('article_page_badge', 'Pusat Edukasi & Berita');
        $articleTitle = Setting::get('article_page_title', 'Artikel & Tips Teknologi Jaringan');
        $articleSubtitle = Setting::get('article_page_subtitle', 'Dapatkan wawasan seputar konfigurasi router, optimasi bandwidth kantor, keamanan jaringan, dan ulasan perangkat IT terbaru.');

        $this->renderAdminView('admin/articles/index', [
            'title' => 'Kelola Artikel & Berita',
            'articles' => $articles,
            'articleBadge' => $articleBadge,
            'articleTitle' => $articleTitle,
            'articleSubtitle' => $articleSubtitle,
        ]);
    }

    public function create(): void
    {
        $this->renderAdminView('admin/articles/create', [
            'title' => 'Tulis Artikel Baru',
        ]);
    }

    public function store(): void
    {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $category = trim($_POST['category'] ?? 'Berita');
        $content = trim($_POST['content'] ?? '');
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_description'] ?? '');
        $metaKeywords = trim($_POST['meta_keywords'] ?? '');
        $status = $_POST['status'] ?? 'published';

        if (empty($title) || empty($content)) {
            FlashHelper::error('Judul dan isi artikel wajib diisi.');
            UrlHelper::redirect('/admin/articles/create');
            return;
        }

        if (empty($slug)) {
            $slug = Sanitizer::slugify($title);
        } else {
            $slug = Sanitizer::slugify($slug);
        }

        if (Article::findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $thumbnail = '';
        if (!empty($_FILES['thumbnail']['name'])) {
            $upload = FileUpload::upload($_FILES['thumbnail'], 'articles');
            if ($upload['success']) {
                $thumbnail = $upload['path'];
            }
        }

        Article::create([
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'content' => $content,
            'thumbnail' => $thumbnail,
            'meta_title' => $metaTitle ?: $title,
            'meta_description' => $metaDesc ?: Sanitizer::truncate($content, 150),
            'meta_keywords' => $metaKeywords,
            'status' => $status,
            'published_at' => ($status === 'published') ? date('Y-m-d H:i:s') : null,
        ]);

        FlashHelper::success("Artikel \"{$title}\" berhasil disimpan.");
        UrlHelper::redirect('/admin/articles');
    }

    public function edit(string|int $id): void
    {
        $article = Article::findById((int) $id);
        if (!$article) {
            FlashHelper::error('Artikel tidak ditemukan.');
            UrlHelper::redirect('/admin/articles');
            return;
        }

        $this->renderAdminView('admin/articles/edit', [
            'title' => 'Edit Artikel: ' . $article['title'],
            'article' => $article,
        ]);
    }

    public function update(string|int $id): void
    {
        $article = Article::findById((int) $id);
        if (!$article) {
            FlashHelper::error('Artikel tidak ditemukan.');
            UrlHelper::redirect('/admin/articles');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $category = trim($_POST['category'] ?? 'Berita');
        $content = trim($_POST['content'] ?? '');
        $metaTitle = trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_description'] ?? '');
        $metaKeywords = trim($_POST['meta_keywords'] ?? '');
        $status = $_POST['status'] ?? 'published';

        if (empty($slug)) {
            $slug = Sanitizer::slugify($title);
        } else {
            $slug = Sanitizer::slugify($slug);
        }

        $updateData = [
            'title' => $title,
            'slug' => $slug,
            'category' => $category,
            'content' => $content,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDesc,
            'meta_keywords' => $metaKeywords,
            'status' => $status,
        ];

        if (!empty($_FILES['thumbnail']['name'])) {
            $upload = FileUpload::upload($_FILES['thumbnail'], 'articles');
            if ($upload['success']) {
                if (!empty($article['thumbnail'])) {
                    FileUpload::delete($article['thumbnail']);
                }
                $updateData['thumbnail'] = $upload['path'];
            }
        }

        Article::updateArticle((int) $id, $updateData);
        FlashHelper::success('Artikel berhasil diperbarui.');
        UrlHelper::redirect('/admin/articles');
    }

    public function delete(string|int $id): void
    {
        $article = Article::findById((int) $id);
        if ($article) {
            if (!empty($article['thumbnail'])) {
                FileUpload::delete($article['thumbnail']);
            }
            Article::deleteArticle((int) $id);
            FlashHelper::success('Artikel berhasil dihapus.');
        }

        UrlHelper::redirect('/admin/articles');
    }

    public function updateSettings(): void
    {
        $badge = trim($_POST['article_page_badge'] ?? '');
        $title = trim($_POST['article_page_title'] ?? '');
        $subtitle = trim($_POST['article_page_subtitle'] ?? '');

        if (!empty($badge)) {
            Setting::set('article_page_badge', $badge, 'articles');
        }
        if (!empty($title)) {
            Setting::set('article_page_title', $title, 'articles');
        }
        Setting::set('article_page_subtitle', $subtitle, 'articles');

        FlashHelper::success('Judul dan kata-kata halaman artikel berhasil diperbarui.');
        UrlHelper::redirect('/admin/articles');
    }
}
