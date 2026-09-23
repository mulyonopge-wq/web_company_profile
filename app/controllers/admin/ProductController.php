<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\FileUpload;
use App\Helpers\FlashHelper;
use App\Helpers\Sanitizer;
use App\Helpers\UrlHelper;
use App\Models\Category;
use App\Models\Product;

class ProductController extends AdminBaseController
{
    public function index(): void
    {
        $categoryFilter = $_GET['category_id'] ?? null;
        $searchQuery = $_GET['q'] ?? null;
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $filters = [
            'q' => $searchQuery,
            'category_id' => $categoryFilter,
        ];

        // Using filter with large perPage for admin management
        $productData = Product::filter($filters, $page, 20);
        $categories = Category::all();

        $this->renderAdminView('admin/products/index', [
            'title' => 'Kelola Produk',
            'products' => $productData['items'],
            'total' => $productData['total'],
            'page' => $productData['page'],
            'totalPages' => $productData['total_pages'],
            'categories' => $categories,
            'filters' => $filters,
        ]);
    }

    public function create(): void
    {
        $categories = Category::all();
        $this->renderAdminView('admin/products/create', [
            'title' => 'Tambah Produk Baru',
            'categories' => $categories,
        ]);
    }

    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $price = (float) ($_POST['price'] ?? 0);
        $discountPrice = !empty($_POST['discount_price']) ? (float) $_POST['discount_price'] : null;
        $stock = (int) ($_POST['stock'] ?? 0);
        $weight = (int) ($_POST['weight'] ?? 500);
        $shortDesc = trim($_POST['short_description'] ?? '');
        $fullDesc = trim($_POST['full_description'] ?? '');
        $spec = trim($_POST['specification'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name) || empty($sku) || $categoryId <= 0 || $price <= 0) {
            FlashHelper::error('Nama produk, SKU, Kategori, dan Harga normal wajib diisi.');
            UrlHelper::redirect('/admin/products/create');
            return;
        }

        if (empty($slug)) {
            $slug = Sanitizer::slugify($name);
        } else {
            $slug = Sanitizer::slugify($slug);
        }

        // Check unique slug
        if (Product::findBySlug($slug)) {
            $slug .= '-' . time();
        }

        // Upload main image
        $mainImagePath = '';
        if (!empty($_FILES['main_image']['name'])) {
            $upload = FileUpload::upload($_FILES['main_image'], 'products');
            if ($upload['success']) {
                $mainImagePath = $upload['path'];
            }
        }

        // Handle Video (URL & File)
        $videoUrl = trim($_POST['video_url'] ?? '');
        $videoFilePath = '';
        if (!empty($_FILES['video_file']['name'])) {
            $vidUpload = FileUpload::uploadVideo($_FILES['video_file'], 'products/videos');
            if ($vidUpload['success']) {
                $videoFilePath = $vidUpload['path'];
            } else {
                FlashHelper::warning('Gagal upload video: ' . $vidUpload['error']);
            }
        }

        $productId = Product::createProduct([
            'sku' => $sku,
            'name' => $name,
            'slug' => $slug,
            'category_id' => $categoryId,
            'short_description' => $shortDesc,
            'full_description' => $fullDesc,
            'specification' => $spec,
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock' => $stock,
            'weight' => $weight,
            'main_image' => $mainImagePath,
            'video_url' => $videoUrl ?: null,
            'video_file' => $videoFilePath ?: null,
            'is_featured' => $isFeatured,
            'is_active' => $isActive,
        ]);

        // Upload extra gallery images if any
        if (!empty($_FILES['gallery_images']['name'][0])) {
            $count = count($_FILES['gallery_images']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!empty($_FILES['gallery_images']['name'][$i])) {
                    $file = [
                        'name' => $_FILES['gallery_images']['name'][$i],
                        'type' => $_FILES['gallery_images']['type'][$i],
                        'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                        'error' => $_FILES['gallery_images']['error'][$i],
                        'size' => $_FILES['gallery_images']['size'][$i],
                    ];
                    $gUpload = FileUpload::upload($file, 'products/gallery');
                    if ($gUpload['success']) {
                        Product::addGalleryImage($productId, $gUpload['path'], $i + 1);
                    }
                }
            }
        }

        FlashHelper::success("Produk \"{$name}\" berhasil ditambahkan.");
        UrlHelper::redirect('/admin/products');
    }

    public function edit(string|int $id): void
    {
        $product = Product::findById((int) $id);
        if (!$product) {
            FlashHelper::error('Produk tidak ditemukan.');
            UrlHelper::redirect('/admin/products');
            return;
        }

        $categories = Category::all();
        $galleryImages = Product::getGalleryImages((int) $id);

        $this->renderAdminView('admin/products/edit', [
            'title' => 'Edit Produk: ' . $product['name'],
            'product' => $product,
            'categories' => $categories,
            'galleryImages' => $galleryImages,
        ]);
    }

    public function update(string|int $id): void
    {
        $product = Product::findById((int) $id);
        if (!$product) {
            FlashHelper::error('Produk tidak ditemukan.');
            UrlHelper::redirect('/admin/products');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $sku = trim($_POST['sku'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $price = (float) ($_POST['price'] ?? 0);
        $discountPrice = !empty($_POST['discount_price']) ? (float) $_POST['discount_price'] : null;
        $stock = (int) ($_POST['stock'] ?? 0);
        $weight = (int) ($_POST['weight'] ?? 500);
        $shortDesc = trim($_POST['short_description'] ?? '');
        $fullDesc = trim($_POST['full_description'] ?? '');
        $spec = trim($_POST['specification'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($slug)) {
            $slug = Sanitizer::slugify($name);
        } else {
            $slug = Sanitizer::slugify($slug);
        }

        $updateData = [
            'sku' => $sku,
            'name' => $name,
            'slug' => $slug,
            'category_id' => $categoryId,
            'short_description' => $shortDesc,
            'full_description' => $fullDesc,
            'specification' => $spec,
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock' => $stock,
            'weight' => $weight,
            'is_featured' => $isFeatured,
            'is_active' => $isActive,
        ];

        // Handle Video URL
        $videoUrl = trim($_POST['video_url'] ?? '');
        $updateData['video_url'] = $videoUrl ?: null;

        // Handle Video File removal
        if (!empty($_POST['remove_video_file'])) {
            if (!empty($product['video_file'])) {
                FileUpload::delete($product['video_file']);
            }
            $updateData['video_file'] = null;
        }

        // Handle Video File upload/replacement
        if (!empty($_FILES['video_file']['name'])) {
            $vidUpload = FileUpload::uploadVideo($_FILES['video_file'], 'products/videos');
            if ($vidUpload['success']) {
                if (!empty($product['video_file'])) {
                    FileUpload::delete($product['video_file']);
                }
                $updateData['video_file'] = $vidUpload['path'];
            } else {
                FlashHelper::warning('Gagal upload video: ' . $vidUpload['error']);
            }
        }

        // Handle Main Image replacement
        if (!empty($_FILES['main_image']['name'])) {
            $upload = FileUpload::upload($_FILES['main_image'], 'products');
            if ($upload['success']) {
                if (!empty($product['main_image'])) {
                    FileUpload::delete($product['main_image']);
                }
                $updateData['main_image'] = $upload['path'];
            }
        }

        Product::updateProduct((int) $id, $updateData);

        // Upload extra gallery images if any
        if (!empty($_FILES['gallery_images']['name'][0])) {
            $count = count($_FILES['gallery_images']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!empty($_FILES['gallery_images']['name'][$i])) {
                    $file = [
                        'name' => $_FILES['gallery_images']['name'][$i],
                        'type' => $_FILES['gallery_images']['type'][$i],
                        'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                        'error' => $_FILES['gallery_images']['error'][$i],
                        'size' => $_FILES['gallery_images']['size'][$i],
                    ];
                    $gUpload = FileUpload::upload($file, 'products/gallery');
                    if ($gUpload['success']) {
                        Product::addGalleryImage((int) $id, $gUpload['path'], $i + 1);
                    }
                }
            }
        }

        FlashHelper::success("Produk \"{$name}\" berhasil diperbarui.");
        UrlHelper::redirect('/admin/products');
    }

    public function delete(string|int $id): void
    {
        $product = Product::findById((int) $id);
        if ($product) {
            if (!empty($product['main_image'])) {
                FileUpload::delete($product['main_image']);
            }
            if (!empty($product['video_file'])) {
                FileUpload::delete($product['video_file']);
            }
            $gallery = Product::getGalleryImages((int) $id);
            foreach ($gallery as $img) {
                FileUpload::delete($img['image_path']);
            }
            Product::deleteProduct((int) $id);
            FlashHelper::success('Produk berhasil dihapus.');
        }

        UrlHelper::redirect('/admin/products');
    }

    public function deleteGalleryImage(string|int $id): void
    {
        $imageId = (int) $id;
        $productId = (int) ($_POST['product_id'] ?? 0);
        $image = Product::fetch("SELECT * FROM `product_images` WHERE `id` = :id", [':id' => $imageId]);
        if ($image) {
            FileUpload::delete($image['image_path']);
            Product::deleteGalleryImage($imageId);
            FlashHelper::success('Foto galeri produk berhasil dihapus.');
        }

        UrlHelper::redirect('/admin/products/edit/' . $productId);
    }
}
