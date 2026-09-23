<?php
declare(strict_types=1);

use Routes\Router;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Router::get('/', 'HomeController@index');
Router::get('/tentang', 'PageController@about');
Router::get('/kontak', 'PageController@contact');
Router::post('/kontak/kirim', 'PageController@contactSubmit');
Router::get('/faq', 'PageController@faq');
Router::get('/kebijakan-privasi', 'PageController@privacy');
Router::get('/syarat-ketentuan', 'PageController@terms');

// Products & Catalog
Router::get('/produk', 'ProductController@index');
Router::get('/kategori/{slug}', 'ProductController@category');
Router::get('/produk/{slug}', 'ProductController@detail');
Router::get('/api/products/search', 'ProductController@searchApi');

// Cart & Checkout
Router::get('/keranjang', 'CartController@index');
Router::post('/keranjang/tambah', 'CartController@add');
Router::post('/keranjang/update', 'CartController@update');
Router::any('/keranjang/hapus/{id}', 'CartController@remove');
Router::any('/keranjang/kosongkan', 'CartController@clear');
Router::get('/checkout', 'CartController@checkout');
Router::post('/checkout/proses', 'CartController@processCheckout');

// Articles / News
Router::get('/artikel', 'ArticleController@index');
Router::get('/artikel/{slug}', 'ArticleController@detail');

// Gallery
Router::get('/galeri', 'GalleryController@index');

// SEO
Router::get('/robots.txt', 'SeoController@robots');
Router::get('/sitemap.xml', 'SeoController@sitemap');


// ==========================================
// ADMIN AUTHENTICATION
// ==========================================
Router::get('/admin/login', 'Admin\AuthController@login');
Router::post('/admin/login/proses', 'Admin\AuthController@processLogin');
Router::get('/admin/logout', 'Admin\AuthController@logout');


// ==========================================
// ADMIN PANEL (CMS)
// ==========================================
Router::get('/admin', 'Admin\DashboardController@index');
Router::get('/admin/dashboard', 'Admin\DashboardController@index');

// Company Profile CMS
Router::get('/admin/company', 'Admin\CompanyController@index');
Router::post('/admin/company/update', 'Admin\CompanyController@update');

// Website Settings & Pages
Router::get('/admin/settings', 'Admin\SettingController@index');
Router::post('/admin/settings/update', 'Admin\SettingController@update');
Router::post('/admin/settings/page', 'Admin\SettingController@updatePage');

// Banners
Router::get('/admin/banners', 'Admin\BannerController@index');
Router::get('/admin/banners/create', 'Admin\BannerController@create');
Router::post('/admin/banners/store', 'Admin\BannerController@store');
Router::get('/admin/banners/edit/{id}', 'Admin\BannerController@edit');
Router::post('/admin/banners/update/{id}', 'Admin\BannerController@update');
Router::post('/admin/banners/delete/{id}', 'Admin\BannerController@delete');

// Categories
Router::get('/admin/categories', 'Admin\CategoryController@index');
Router::get('/admin/categories/create', 'Admin\CategoryController@create');
Router::post('/admin/categories/store', 'Admin\CategoryController@store');
Router::get('/admin/categories/edit/{id}', 'Admin\CategoryController@edit');
Router::post('/admin/categories/update/{id}', 'Admin\CategoryController@update');
Router::post('/admin/categories/delete/{id}', 'Admin\CategoryController@delete');

// Products
Router::get('/admin/products', 'Admin\ProductController@index');
Router::get('/admin/products/create', 'Admin\ProductController@create');
Router::post('/admin/products/store', 'Admin\ProductController@store');
Router::get('/admin/products/edit/{id}', 'Admin\ProductController@edit');
Router::post('/admin/products/update/{id}', 'Admin\ProductController@update');
Router::post('/admin/products/delete/{id}', 'Admin\ProductController@delete');
Router::post('/admin/products/delete-image/{id}', 'Admin\ProductController@deleteGalleryImage');

// Orders
Router::get('/admin/orders', 'Admin\OrderController@index');
Router::get('/admin/orders/detail/{id}', 'Admin\OrderController@detail');
Router::post('/admin/orders/status/{id}', 'Admin\OrderController@updateStatus');

// Customers
Router::get('/admin/customers', 'Admin\CustomerController@index');

// Articles
Router::get('/admin/articles', 'Admin\ArticleController@index');
Router::get('/admin/articles/create', 'Admin\ArticleController@create');
Router::post('/admin/articles/store', 'Admin\ArticleController@store');
Router::get('/admin/articles/edit/{id}', 'Admin\ArticleController@edit');
Router::post('/admin/articles/update/{id}', 'Admin\ArticleController@update');
Router::post('/admin/articles/delete/{id}', 'Admin\ArticleController@delete');

// Galleries
Router::get('/admin/galleries', 'Admin\GalleryController@index');
Router::get('/admin/galleries/create', 'Admin\GalleryController@create');
Router::post('/admin/galleries/store', 'Admin\GalleryController@store');
Router::post('/admin/galleries/delete/{id}', 'Admin\GalleryController@delete');

// Users / Admins
Router::get('/admin/users', 'Admin\UserController@index');
Router::get('/admin/users/create', 'Admin\UserController@create');
Router::post('/admin/users/store', 'Admin\UserController@store');
Router::get('/admin/users/edit/{id}', 'Admin\UserController@edit');
Router::post('/admin/users/update/{id}', 'Admin\UserController@update');
Router::post('/admin/users/delete/{id}', 'Admin\UserController@delete');

// System & GitHub Updates
Router::get('/admin/update', 'Admin\UpdateController@index');
Router::post('/admin/update/check', 'Admin\UpdateController@check');
Router::post('/admin/update/pull', 'Admin\UpdateController@pull');
Router::post('/admin/update/reset', 'Admin\UpdateController@resetHard');

