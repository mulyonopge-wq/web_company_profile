<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Article;
use App\Models\Gallery;

class HomeController extends BaseController
{
    public function index(): void
    {
        $banners = Banner::getActive();
        $categories = Category::getActiveWithCount();
        $featuredProducts = Product::getFeatured(8);
        $latestProducts = Product::getLatest(8);
        $latestArticles = Article::getPublished(3);
        $galleries = array_slice(Gallery::getActive(), 0, 6);

        $this->renderView('home/index', [
            'is_home' => true,
            'banners' => $banners,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'latestArticles' => $latestArticles,
            'galleries' => $galleries,
        ]);
    }
}
