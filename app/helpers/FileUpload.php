<?php
declare(strict_types=1);

namespace App\Helpers;

use Exception;

class FileUpload
{
    private static array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    private static array $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];
    private static int $maxSize = 5 * 1024 * 1024; // 5 MB

    public static function upload(array $file, string $subfolder = 'general'): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Parameter upload file tidak valid.'];
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'error' => 'Tidak ada file yang dipilih.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'error' => 'Ukuran file melebihi batas maksimal server.'];
            default:
                return ['success' => false, 'error' => 'Terjadi kesalahan saat upload file.'];
        }

        if ($file['size'] > self::$maxSize) {
            return ['success' => false, 'error' => 'Ukuran file maksimal adalah 5MB.'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowedExtensions, true)) {
            return ['success' => false, 'error' => 'Ekstensi file tidak didukung. Hanya JPG, PNG, dan WEBP yang diperbolehkan.'];
        }

        // Validate MIME type safely (finfo / mime_content_type / getimagesize)
        $mime = '';
        if (function_exists('finfo_open')) {
            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime = @finfo_file($finfo, $file['tmp_name']) ?: '';
                @finfo_close($finfo);
            }
        } elseif (function_exists('mime_content_type')) {
            $mime = @mime_content_type($file['tmp_name']) ?: '';
        } elseif (function_exists('getimagesize')) {
            $imageInfo = @getimagesize($file['tmp_name']);
            $mime = $imageInfo['mime'] ?? '';
        }

        if (!empty($mime) && !in_array($mime, self::$allowedMimes, true)) {
            return ['success' => false, 'error' => 'Tipe konten file tidak valid atau bukan gambar yang valid.'];
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/' . trim($subfolder, '/');
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'error' => 'Gagal membuat direktori upload.'];
            }
        }

        // Secure unique filename
        $fileName = sprintf('%s_%s.%s', bin2hex(random_bytes(10)), time(), $extension);
        $destination = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'error' => 'Gagal memindahkan file yang diunggah.'];
        }

        // Return relative path from uploads directory
        return [
            'success' => true,
            'path' => trim($subfolder, '/') . '/' . $fileName,
            'file_name' => $fileName,
        ];
    }

    private static array $allowedVideoExtensions = ['mp4', 'webm', 'ogg'];
    private static array $allowedVideoMimes = [
        'video/mp4',
        'video/webm',
        'video/ogg',
    ];
    private static int $maxVideoSize = 50 * 1024 * 1024; // 50 MB

    public static function uploadVideo(array $file, string $subfolder = 'products/videos'): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Parameter upload video tidak valid.'];
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'error' => 'Tidak ada file video yang dipilih.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'error' => 'Ukuran file video melebihi batas maksimal server.'];
            default:
                return ['success' => false, 'error' => 'Terjadi kesalahan saat upload video.'];
        }

        if ($file['size'] > self::$maxVideoSize) {
            return ['success' => false, 'error' => 'Ukuran file video maksimal adalah 50MB.'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowedVideoExtensions, true)) {
            return ['success' => false, 'error' => 'Ekstensi file video tidak didukung. Hanya MP4, WEBM, dan OGG yang diperbolehkan.'];
        }

        $mime = '';
        if (function_exists('finfo_open')) {
            $finfo = @finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo) {
                $mime = @finfo_file($finfo, $file['tmp_name']) ?: '';
                @finfo_close($finfo);
            }
        } elseif (function_exists('mime_content_type')) {
            $mime = @mime_content_type($file['tmp_name']) ?: '';
        }

        if (!empty($mime) && !in_array($mime, self::$allowedVideoMimes, true)) {
            return ['success' => false, 'error' => 'Tipe konten file bukan video yang valid (' . $mime . ').'];
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/' . trim($subfolder, '/');
        if (!is_dir($uploadDir)) {
            if (!@mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'error' => 'Gagal membuat direktori upload video.'];
            }
        }

        $fileName = sprintf('vid_%s_%s.%s', bin2hex(random_bytes(8)), time(), $extension);
        $destination = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'error' => 'Gagal memindahkan file video yang diunggah.'];
        }

        return [
            'success' => true,
            'path' => trim($subfolder, '/') . '/' . $fileName,
            'file_name' => $fileName,
        ];
    }

    public static function delete(?string $relativePath): bool
    {
        if (empty($relativePath)) {
            return false;
        }

        // Sanitize against directory traversal
        $relativePath = str_replace(['../', '..\\'], '', $relativePath);
        $fullPath = dirname(__DIR__, 2) . '/public/uploads/' . ltrim($relativePath, '/');

        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }
}
