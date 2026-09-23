-- ==============================================================
-- Database Schema & Initial Data for Company Profile + Marketplace
-- Compatible with MySQL 5.7+ / 8.0+ and MariaDB 10.3+
-- ==============================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `customers`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `banners`;
DROP TABLE IF EXISTS `articles`;
DROP TABLE IF EXISTS `galleries`;
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `admins`;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------------
-- Table: admins
-- -------------------------------------------------------------
CREATE TABLE `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'admin',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_admin_username` (`username`),
  INDEX `idx_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: settings
-- -------------------------------------------------------------
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` LONGTEXT NULL,
  `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_setting_key` (`setting_key`),
  INDEX `idx_setting_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: pages (Static CMS Pages)
-- -------------------------------------------------------------
CREATE TABLE `pages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `title` VARCHAR(200) NOT NULL,
  `content` LONGTEXT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_page_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: banners
-- -------------------------------------------------------------
CREATE TABLE `banners` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `subtitle` TEXT NULL,
  `image` VARCHAR(255) NOT NULL,
  `button_text` VARCHAR(100) NULL DEFAULT 'Lihat Produk',
  `button_url` VARCHAR(255) NULL DEFAULT '/produk',
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_banner_order` (`sort_order`),
  INDEX `idx_banner_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: categories
-- -------------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `icon_image` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_category_slug` (`slug`),
  INDEX `idx_category_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: products
-- -------------------------------------------------------------
CREATE TABLE `products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `sku` VARCHAR(50) NOT NULL UNIQUE,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL UNIQUE,
  `category_id` INT UNSIGNED NOT NULL,
  `short_description` TEXT NULL,
  `full_description` LONGTEXT NULL,
  `specification` LONGTEXT NULL,
  `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `discount_price` DECIMAL(12,2) NULL DEFAULT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `weight` INT NOT NULL DEFAULT 500 COMMENT 'Weight in grams',
  `main_image` VARCHAR(255) NULL,
  `video_url` VARCHAR(255) NULL,
  `video_file` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_prod_category` (`category_id`),
  INDEX `idx_prod_slug` (`slug`),
  INDEX `idx_prod_sku` (`sku`),
  INDEX `idx_prod_featured` (`is_featured`),
  INDEX `idx_prod_active` (`is_active`),
  INDEX `idx_prod_price` (`price`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: product_images
-- -------------------------------------------------------------
CREATE TABLE `product_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_prod_img_fk` (`product_id`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: customers
-- -------------------------------------------------------------
CREATE TABLE `customers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `email` VARCHAR(150) NULL,
  `address` TEXT NOT NULL,
  `total_orders` INT NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_customer_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: orders
-- -------------------------------------------------------------
CREATE TABLE `orders` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `customer_email` VARCHAR(150) NULL,
  `customer_address` TEXT NOT NULL,
  `notes` TEXT NULL,
  `subtotal` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pending', 'processing', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_order_number` (`order_number`),
  INDEX `idx_order_status` (`status`),
  INDEX `idx_order_created` (`created_at`),
  CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: order_items
-- -------------------------------------------------------------
CREATE TABLE `order_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NULL,
  `product_name` VARCHAR(200) NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_item_order` (`order_id`),
  CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: articles
-- -------------------------------------------------------------
CREATE TABLE `articles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(250) NOT NULL,
  `slug` VARCHAR(250) NOT NULL UNIQUE,
  `thumbnail` VARCHAR(255) NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'Berita',
  `content` LONGTEXT NOT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `meta_keywords` VARCHAR(255) NULL,
  `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
  `published_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_article_slug` (`slug`),
  INDEX `idx_article_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: galleries
-- -------------------------------------------------------------
CREATE TABLE `galleries` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  `category` VARCHAR(100) NOT NULL DEFAULT 'Kegiatan',
  `image` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_gallery_active` (`is_active`),
  INDEX `idx_gallery_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: teams (Tim Manajemen & Pimpinan / Pengurus)
-- -------------------------------------------------------------
CREATE TABLE `teams` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `position` VARCHAR(150) NOT NULL,
  `photo` VARCHAR(255) NULL,
  `bio` TEXT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_team_order` (`sort_order`),
  INDEX `idx_team_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==============================================================
-- INITIAL SEED DATA
-- ==============================================================

-- 1. Default Admin Account (Username: admin | Password: Admin12345!)
INSERT INTO `admins` (`id`, `username`, `email`, `password`, `name`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@solusitekno.co.id', '$2y$10$NZ8NVAUJwFPQ5IwrODP15exlWXraz5NNZKwQWSaJE7UrVSDKESvz.', 'Administrator Utama', 'admin', NOW(), NOW());

-- 2. Website & Company Profile Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_name', 'PT Solusi Tekno Nusantara', 'general'),
('site_tagline', 'Solusi Infrastruktur Jaringan & Teknologi Terintegrasi', 'general'),
('site_logo', '', 'general'),
('site_favicon', '', 'general'),
('site_description', 'Penyedia perangkat jaringan komputer, router mikrotik, switch manageable, dan perlengkapan IT berkualitas untuk bisnis Anda.', 'general'),
('site_keywords', 'mikrotik, router, switch cisco, wifi ap, komputer kantor, networking jakarta', 'general'),

('company_name', 'PT Solusi Tekno Nusantara', 'company'),
('company_slogan', 'Menghubungkan Bisnis Anda dengan Teknologi Andal & Efisien', 'company'),
('company_short_description', 'Penyedia terkemuka perangkat jaringan, router, switch, server, dan solusi integrasi IT skala UMKM hingga Enterprise di Indonesia.', 'company'),
('company_long_description', '<p><strong>PT Solusi Tekno Nusantara</strong> berdiri sejak tahun 2018 dengan visi menjadi akselerator transformasi digital bagi pelaku usaha di tanah air. Kami mengkhususkan diri pada distribusi perangkat keras jaringan berkualifikasi tinggi, instalasi infrastruktur kabel terstruktur, dan perangkat IT pendukung produktivitas perkantoran.</p><p>Didukung oleh tim teknisi bersertifikasi internasional (MTCNA, CCNA), kami memastikan setiap solusi yang dihadirkan dapat beroperasi secara optimal, stabil, dan memiliki garansi terjamin.</p>', 'company'),
('company_established_year', '2018', 'company'),
('company_address', 'Jl. Boulevard IT No. 88, Kawasan Niaga Digital, Jakarta Selatan 12340', 'company'),
('company_whatsapp', '081234567890', 'company'),
('company_phone', '(021) 7890-1234', 'company'),
('company_email', 'info@solusitekno.co.id', 'company'),
('company_maps_embed', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.28639967732!2d106.759478!3d-6.2293867!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e49fe3ddb3%3A0x73d5749ac9470868!2sJakarta%20Selatan!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid\" width=\"100%\" height=\"350\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 'company'),
('company_hours', 'Senin - Jumat: 08:30 - 17:30 WIB\nSabtu: 09:00 - 15:00 WIB\nMinggu & Hari Libur: Tutup', 'company'),
('company_vision', 'Menjadi mitra penyedia solusi jaringan dan teknologi informasi nomor satu di Indonesia yang terpercaya, adaptif, dan berorientasi pada kepuasan pelanggan.', 'company'),
('company_mission', '1. Menyediakan ragam perangkat IT & networking berkualitas tinggi dengan garansi resmi dan harga terbaik.\n2. Memberikan layanan konsultasi teknis serta pendampingan implementasi yang profesional.\n3. Membangun kemitraan strategis jangka panjang yang saling menguntungkan dengan pelaku bisnis UMKM dan enterprise.', 'company'),
('company_values', 'Integritas, Kecepatan Layanan, Kualitas Tanpa Kompromi, dan Tanggung Jawab Purna Jual.', 'company'),
('company_advantages', '1. Produk 100% Original & Garansi Resmi\n2. Ready Stock Siap Kirim ke Seluruh Indonesia\n3. Gratis Konsultasi Pemilihan Perangkat Jaringan\n4. Layanan Bantuan Responsif via WhatsApp Support', 'company'),

('social_facebook', 'https://facebook.com/solusitekno', 'social'),
('social_instagram', 'https://instagram.com/solusitekno', 'social'),
('social_tiktok', 'https://tiktok.com/@solusitekno', 'social'),
('social_youtube', 'https://youtube.com/@solusitekno', 'social'),

('footer_text', 'Mitra terpercaya pengadaan perangkat networking, router, switch, dan perangkat IT bisnis di seluruh Indonesia.', 'footer'),
('footer_copyright', '© 2026 PT Solusi Tekno Nusantara. All Rights Reserved.', 'footer');

-- 3. Static Pages Content
INSERT INTO `pages` (`slug`, `title`, `content`, `meta_title`, `meta_description`) VALUES
('tentang-kami', 'Tentang Kami', 'PT Solusi Tekno Nusantara adalah perusahaan penyedia infrastruktur jaringan dan solusi teknologi informasi terpadu yang telah melayani ratusan klien di seluruh Indonesia.', 'Tentang PT Solusi Tekno Nusantara', 'Profil lengkap, sejarah, visi misi dan komitmen PT Solusi Tekno Nusantara.'),
('faq', 'Pertanyaan Umum (FAQ)', '<h3>1. Apakah produk yang dijual memiliki garansi resmi?</h3><p>Ya, seluruh produk kami memiliki garansi resmi distributor resmi di Indonesia dengan masa garansi 1 hingga 2 tahun sesuai tipe produk.</p><h3>2. Bagaimana alur pemesanan produk?</h3><p>Anda dapat memilih produk yang diinginkan, menambahkannya ke keranjang, dan melakukan checkout. Setelah checkout, sistem akan mengarahkan Anda ke WhatsApp Sales kami untuk konfirmasi ketersediaan stok dan alamat kirim.</p><h3>3. Metode pembayaran apa saja yang diterima?</h3><p>Kami menerima pembayaran melalui Transfer Bank BCA, Mandiri, BNI, serta pembayaran perusahaan (PO/Invoice resmi) untuk klien korporasi.</p><h3>4. Apakah melayani pengiriman ke luar kota/pulau?</h3><p>Ya, kami bekerja sama dengan berbagai ekspedisi terpercaya (JNE, J&T, SiCepat, TiKi, Cargo) dengan asuransi pengiriman.</p>', 'FAQ - Pertanyaan Umum', 'Pertanyaan seputar pemesanan, pengiriman, dan garansi produk.'),
('kebijakan-privasi', 'Kebijakan Privasi', '<p>Kami sangat menghargai privasi setiap pelanggan. Informasi yang Anda berikan saat melakukan pemesanan (nama, nomor telepon, alamat kirim, email) hanya digunakan untuk memproses pesanan dan tidak akan pernah diperjualbelikan kepada pihak ketiga.</p><p>Data Anda dilindungi oleh standar keamanan data industri.</p>', 'Kebijakan Privasi', 'Kebijakan perlindungan data dan privasi pelanggan PT Solusi Tekno Nusantara.'),
('syarat-ketentuan', 'Syarat & Ketentuan', '<p>1. <strong>Pemesanan:</strong> Setiap pesanan yang masuk melalui WhatsApp akan dikonfirmasi oleh tim sales resmi kami.</p><p>2. <strong>Pembayaran:</strong> Pembayaran wajib dikirimkan ke rekening resmi perusahaan sebelum barang dikirim.</p><p>3. <strong>Retur & Garansi:</strong> Klaim garansi barang rusak akibat cacat produksi dapat diajukan maksimal 7 hari kerja setelah barang diterima dengan menyertakan video unboxing.</p>', 'Syarat & Ketentuan', 'Syarat dan ketentuan pembelian di PT Solusi Tekno Nusantara.');

-- 4. Banners
INSERT INTO `banners` (`id`, `title`, `subtitle`, `image`, `button_text`, `button_url`, `sort_order`, `is_active`) VALUES
(1, 'Solusi Jaringan Bisnis & Kantor Modern', 'Dapatkan performa internet stabil dan aman dengan Router MikroTik dan Switch Enterprise terbaik.', '', 'Lihat Katalog Produk', '/produk', 1, 1),
(2, 'Paket Hemat Infrastruktur IT UMKM', 'Upgrade konektivitas kantor Anda dengan harga terjangkau dan dukungan teknis terpercaya.', '', 'Hubungi Kami', '/kontak', 2, 1),
(3, 'Garansi Resmi & Pengiriman Cepat', 'Produk original bergaransi resmi distributor dengan jangkauan pengiriman ke seluruh Nusantara.', '', 'Mulai Belanja', '/produk', 3, 1);

-- 5. Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `icon_image`, `sort_order`, `is_active`) VALUES
(1, 'Networking & Router', 'networking-router', '', 1, 1),
(2, 'Komputer & Aksesoris', 'komputer-aksesoris', '', 2, 1),
(3, 'Power & Backup UPS', 'power-backup-ups', '', 3, 1);

-- 6. Products
INSERT INTO `products` (`id`, `sku`, `name`, `slug`, `category_id`, `short_description`, `full_description`, `specification`, `price`, `discount_price`, `stock`, `weight`, `main_image`, `is_featured`, `is_active`) VALUES
(1, 'NET-MTK-001', 'MikroTik RouterBOARD RB750Gr3 (hEX)', 'mikrotik-routerboard-rb750gr3-hex', 1,
'Router 5 port Gigabit Ethernet tangguh, didukung prosesor dual-core 880MHz untuk jaringan kantor & warnet.',
'MikroTik hEX (RB750Gr3) adalah router gigabit Ethernet 5 port untuk lokasi yang tidak memerlukan konektivitas nirkabel. Perangkat ini memiliki port USB ukuran penuh. Versi revisi baru dari hEX ini membawa beberapa peningkatan dalam performa. Router ini terjangkau, kecil, dan mudah digunakan, namun sekaligus hadir dengan CPU dual core 880MHz yang sangat bertenaga dan RAM 256MB.',
'CPU: Dual Core MT7621A 880 MHz\nRAM: 256 MB\nStorage: 16 MB Flash & MicroSD slot\nEthernet: 5 x 10/100/1000 Gigabit\nOperating System: RouterOS License Level 4\nMax Power Consumption: 10W\nPoE In: 8-30V',
850000.00, 785000.00, 25, 300, '', 1, 1),

(2, 'NET-TPL-002', 'TP-Link Archer C6 AC1200 MU-MIMO Gigabit Router', 'tp-link-archer-c6-ac1200-mu-mimo-gigabit-router', 1,
'Wireless Dual Band Router AC1200 dengan 4 antena eksternal untuk cakupan WiFi luas dan koneksi stabil.',
'Archer C6 menciptakan jaringan yang andal dan sangat cepat didukung oleh teknologi Wi-Fi 802.11ac. Pita 2.4GHz memberikan kecepatan hingga 300Mbps, siap untuk tugas sehari-hari seperti email dan penjelajahan web, sedangkan pita 5GHz memberikan kecepatan hingga 867Mbps, ideal untuk streaming video HD dan game online bebas lag.',
'Standards: Wi-Fi 5 (IEEE 802.11ac/n/a 5 GHz, IEEE 802.11n/b/g 2.4 GHz)\nWiFi Speeds: 5 GHz: 867 Mbps, 2.4 GHz: 300 Mbps\nAntennas: 4 External Fixed Antennas\nEthernet Ports: 1 x Gigabit WAN, 4 x Gigabit LAN\nModes: Router Mode, Access Point Mode',
525000.00, 489000.00, 40, 600, '', 1, 1),

(3, 'NET-CIS-003', 'Cisco Catalyst 2960-X 24 Port Gigabit Switch', 'cisco-catalyst-2960-x-24-port-gigabit-switch', 1,
'Switch manageable layer 2 enterprise dengan 24 port 10/100/1000 Gigabit dan 4 link uplink SFP 1G.',
'Cisco Catalyst 2960-X Series Switches adalah switch Gigabit Ethernet dengan konfigurasi tetap dan dapat ditumpuk yang menyediakan akses kelas enterprise untuk aplikasi kampus dan cabang. Dirancang untuk efisiensi operasional guna menurunkan total biaya kepemilikan.',
'Ports: 24 x 10/100/1000 Gigabit Ethernet\nUplinks: 4 x 1G SFP\nForwarding Bandwidth: 108 Gbps\nRAM: 512 MB\nFlash: 128 MB\nForm Factor: 1U Rackmount\nRedundant Power: Supported',
4200000.00, 3950000.00, 8, 4500, '', 1, 1),

(4, 'ACC-LOG-004', 'Logitech MK295 Silent Wireless Keyboard and Mouse Combo', 'logitech-mk295-silent-wireless-combo', 2,
'Kombo keyboard & mouse nirkabel dengan teknologi SilentTouch yang menghilangkan 90% kebisingan mengetik.',
'Tetap fokus pada pekerjaan Anda dan hilangkan gangguan. Temui Logitech MK295 Silent Wireless Combo yang dilengkapi SilentTouch—teknologi eksklusif Logitech yang menghilangkan lebih dari 90% suara keyboard dan mouse. Ini adalah sensasi klik dan mengetik yang sama dengan kombo terlaris di dunia tanpa suara klik dan mengetik yang mengganggu.',
'Connectivity: 2.4 GHz Wireless Nano USB (10m range)\nBattery Life: Keyboard 36 Months, Mouse 18 Months\nKeyboard: Spill-resistant design, 8 shortcut keys\nMouse: Compact ergonomic contoured shape\nOS Compatibility: Windows 10/11, Chrome OS',
490000.00, 445000.00, 30, 800, '', 1, 1),

(5, 'PWR-APC-005', 'APC Back-UPS 650VA / 360W (BX650LI-MS)', 'apc-back-ups-650va-360w-bx650li-ms', 3,
'UPS andal penstabil tegangan (AVR) dan pencegah mati mendadak untuk router, server mini, dan PC kerja.',
'Lindungi perangkat elektronik berharga Anda dari bahaya lonjakan daya, fluktuasi listrik, dan pemadaman mendadak. APC Back-UPS 650VA memberikan daya baterai cadangan yang cukup bagi Anda untuk menyimpan file dan mematikan sistem secara aman.',
'Output Capacity: 650VA / 360 Watts\nNominal Output Voltage: 230V\nTopology: Line Interactive (Automatic Voltage Regulation)\nOutput Connections: 2 Universal Outlets\nBattery Type: Maintenance-free sealed Lead-Acid\nTypical Recharge Time: 4-6 hours',
980000.00, 920000.00, 15, 4800, '', 1, 1);

-- 7. Customers
INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`, `total_orders`, `created_at`, `updated_at`) VALUES
(1, 'Budi Santoso', '081298765432', 'budi.santoso@gmail.com', 'Komp. Graha Indah Blok C3 No. 12, Kebayoran Baru, Jakarta Selatan', 1, NOW(), NOW()),
(2, 'PT Cipta Kreasi Mandiri (Rian)', '085712349988', 'procurement@ciptakreasi.co.id', 'Gedung Wisma Niaga Lt. 4, Jl. Sudirman No. 45, Jakarta Pusat', 1, NOW(), NOW());

-- 8. Orders & Order Items
INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `customer_name`, `customer_phone`, `customer_email`, `customer_address`, `notes`, `subtotal`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 'INV-20260920-001', 1, 'Budi Santoso', '081298765432', 'budi.santoso@gmail.com', 'Komp. Graha Indah Blok C3 No. 12, Kebayoran Baru, Jakarta Selatan', 'Tolong dikirim dengan packing kayu dan bubble wrap tebal ya.', 785000.00, 785000.00, 'completed', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 1 DAY),
(2, 'INV-20260922-002', 2, 'PT Cipta Kreasi Mandiri (Rian)', '085712349988', 'procurement@ciptakreasi.co.id', 'Gedung Wisma Niaga Lt. 4, Jl. Sudirman No. 45, Jakarta Pusat', 'Mohon sertakan kwitansi faktur pajak stempel basah.', 4395000.00, 4395000.00, 'processing', NOW(), NOW());

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 'MikroTik RouterBOARD RB750Gr3 (hEX)', 785000.00, 1, 785000.00, NOW() - INTERVAL 2 DAY),
(2, 2, 3, 'Cisco Catalyst 2960-X 24 Port Gigabit Switch', 3950000.00, 1, 3950000.00, NOW()),
(3, 2, 4, 'Logitech MK295 Silent Wireless Keyboard and Mouse Combo', 445000.00, 1, 445000.00, NOW());

-- 9. Articles
INSERT INTO `articles` (`id`, `title`, `slug`, `thumbnail`, `category`, `content`, `meta_title`, `meta_description`, `meta_keywords`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, '5 Alasan Mengapa Kantor Anda Wajib Menggunakan Router MikroTik', '5-alasan-kantor-wajib-menggunakan-router-mikrotik', '', 'Tips & Edukasi',
'<p>Bagi pemilik bisnis atau manajer IT kantor, menjaga koneksi internet tetap stabil dan terbagi merata adalah kunci produktivitas kerja harian. Salah satu perangkat yang paling banyak direkomendasikan adalah <strong>MikroTik RouterBOARD</strong>.</p><h3>1. Bandwidth Management yang Presisi</h3><p>MikroTik dilengkapi fitur Simple Queue dan Queue Tree yang memungkinkan administrator jaringan membatasi kecepatan per divisi atau per pengguna secara fleksibel.</p><h3>2. Fitur Keamanan Firewall Komprehensif</h3><p>Dengan RouterOS, Anda dapat memblokir situs-situs yang mengganggu produktivitas, serangan brute-force dari luar, hingga membatasi akses jam kerja.</p><h3>3. Dukungan Multi-WAN & Failover</h3><p>Kantor Anda dapat berlangganan 2 ISP sekaligus (misal IndiHome dan Biznet). Jika salah satu ISP down, MikroTik akan otomatis mengalihkan koneksi ke jalur cadangan tanpa terputus.</p><h3>4. Efisiensi Biaya (Cost-Effective)</h3><p>Dibandingkan router enterprise lain yang membutuhkan lisensi tahunan mahal, lisensi RouterOS MikroTik berlaku seumur hidup (lifetime license).</p>',
'5 Alasan Kantor Wajib Menggunakan MikroTik - Panduan IT',
'Panduan memilih router kantor terbaik. Pelajari keunggulan MikroTik untuk bandwidth management, failover ISP, dan firewall kantor.',
'mikrotik kantor, router bisnis, kelebihan mikrotik, bandwidth management, failover isp',
'published', NOW(), NOW(), NOW()),

(2, 'Panduan Memilih UPS yang Tepat untuk Melindungi Server dan PC Kerja', 'panduan-memilih-ups-yang-tepat-untuk-server-dan-pc', '', 'Teknologi',
'<p>Listrik yang sering mati mendadak atau mengalami lonjakan tegangan (voltage spike) adalah musuh utama perangkat elektronik komputer. Tanpa perlindungan yang tepat, motherboard, power supply, dan hard disk dapat mengalami kerusakan permanen atau kehilangan data krusial.</p><h3>1. Hitung Total Konsumsi Daya (Watt)</h3><p>Sebelum membeli UPS, buat daftar perangkat yang akan dihubungkan beserta watt masing-masing. Pastikan kapasitas UPS setidaknya 20-30% lebih besar dari total daya perangkat.</p><h3>2. Kenali Jenis Topologi UPS</h3><p>Untuk komputer biasa, jenis <em>Standby/Offline</em> atau <em>Line-Interactive</em> sudah mencukupi. Namun untuk server mission-critical, sangat disarankan memilih <em>Online Double-Conversion</em> untuk stabilitas gelombang listrik murni (pure sine wave).</p><h3>3. Perhatikan Fitur AVR (Automatic Voltage Regulation)</h3><p>Fitur AVR berguna untuk menstabilkan voltase listrik yang naik-turun tanpa harus selalu menguras baterai utama.</p>',
'Panduan Memilih UPS Terbaik untuk Server dan PC Kerja',
'Tips dan panduan praktis memilih UPS yang tepat agar perangkat komputer dan server kantor terlindungi dari listrik mati mendadak.',
'ups komputer, panduan ups, apc back ups, stabilizer listrik, proteksi server',
'published', NOW(), NOW(), NOW());

-- 10. Galleries
INSERT INTO `galleries` (`id`, `title`, `description`, `category`, `image`, `is_active`, `sort_order`) VALUES
(1, 'Workshop & Lab Jaringan', 'Ruang konfigurasi dan pengujian perangkat jaringan sebelum dikirimkan ke pelanggan.', 'Fasilitas', '', 1, 1),
(2, 'Warehouse & Stock Center', 'Gudang penyimpanan stok router, kabel UTP, switch, dan UPS yang siap dikirim.', 'Fasilitas', '', 1, 2),
(3, 'Instalasi Jaringan Klien Korporat', 'Dokumentasi perapihan rack server dan instalasi kabel LAN terstruktur oleh teknisi kami.', 'Proyek', '', 1, 3),
(4, 'Sesi Konsultasi & Demo Produk', 'Diskusi teknis solusi infrastruktur internet bersama klien UMKM dan instansi.', 'Kegiatan', '', 1, 4);

-- 11. Teams (Tim Manajemen & Pimpinan / Pengurus)
INSERT INTO `teams` (`id`, `name`, `position`, `photo`, `bio`, `sort_order`, `is_active`) VALUES
(1, 'Dr. H. Muhammad Arifin, M.M.', 'Komisaris Utama', '', 'Berpengalaman lebih dari 20 tahun dalam tata kelola korporasi, kepemimpinan strategis, dan investasi teknologi.', 1, 1),
(2, 'Ir. Hendra Gunawan, S.T., M.T.', 'Direktur Utama', '', 'Memimpin arah strategis perusahaan, kemitraan global, dan inovasi integrasi infrastruktur digital di Indonesia.', 2, 1),
(3, 'Bambang Pratama, S.Kom., CCNA', 'Direktur Operasional & Teknologi', '', 'Mengawasi operasional logistik, implementasi solusi jaringan enterprise, dan standardisasi kualitas layanan purna jual.', 3, 1),
(4, 'Siti Rahmawati, S.E., Ak.', 'Manajer Keuangan & Administrasi', '', 'Bertanggung jawab atas efisiensi finansial, kepatuhan akuntansi korporat, dan manajemen hubungan kemitraan perbankan.', 4, 1);

