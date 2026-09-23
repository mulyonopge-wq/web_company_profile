# Company Profile + Mini Marketplace + Admin Panel (CMS)

Aplikasi web profesional yang menggabungkan:
1. **Company Profile** (Profil Perusahaan, Visi, Misi, Nilai, Keunggulan, Galeri, FAQ, Kontak)
2. **Mini Marketplace / Toko Online** (Katalog hingga 100 produk, kategori, filter harga, live search AJAX, keranjang belanja session, checkout pesanan langsung ke WhatsApp)
3. **Admin Panel / CMS** (Pengaturan website, profil perusahaan, banner slider, produk & galeri gambar, kategori, manajemen pesanan, artikel/berita, statistik & grafik penjualan)

Dibangun menggunakan arsitektur **MVC PHP Native** (tanpa framework berat seperti Laravel) sehingga ringan, terstruktur rapi, aman, dan mudah dideploy di shared hosting maupun VPS.

---

## 1. Requirement Server

- **PHP**: Versi 8.0, 8.1, 8.2, atau lebih baru
- **Ekstensi PHP**:
  - `pdo` & `pdo_mysql`
  - `fileinfo` (untuk validasi MIME type upload)
  - `mbstring`
  - `openssl`
  - `json`
- **Database**: MySQL 5.7+ / 8.0+ atau MariaDB 10.3+
- **Web Server**: Apache (dengan modul `mod_rewrite` aktif) atau Nginx
- **Browser**: Modern browser (Chrome, Firefox, Edge, Safari) dengan JavaScript aktif

---

## 2. Cara Membuat Database

### Melalui MySQL CLI:
```sql
CREATE DATABASE company_marketplace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Melalui phpMyAdmin:
1. Buka browser dan akses `http://localhost/phpmyadmin`.
2. Klik menu **Databases** / **Basis data**.
3. Masukkan nama database: `company_marketplace`.
4. Pilih collation: `utf8mb4_unicode_ci`.
5. Klik **Create** / **Buat**.

---

## 3. Cara Import `database.sql`

### Opsi A: Menggunakan Script Otomatis (Rekomendasi)
Jalankan perintah ini di terminal / command prompt proyek:
```bash
php database/install.php
```
Script ini akan otomatis membuat database `company_marketplace`, membuat seluruh tabel, dan mengisi data awal (seed).

### Opsi B: Menggunakan CLI `mysql`
```bash
mysql -u root -p company_marketplace < database/database.sql
```

### Opsi C: Menggunakan phpMyAdmin
1. Buka database `company_marketplace` di phpMyAdmin.
2. Klik tab **Import**.
3. Klik **Choose File** dan pilih file `database/database.sql`.
4. Klik tombol **Import** di bagian bawah.

---

## 4. Konfigurasi Database & Environment (`.env`)

Duplikat file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*(Di Windows PowerShell: `copy .env.example .env`)*

Buka file `.env` dan sesuaikan kredensial server Anda:
```ini
APP_NAME="PT Solusi Tekno Nusantara"
APP_ENV="development" # Ubah ke "production" saat live
APP_DEBUG="true"      # Ubah ke "false" saat live di server publik
APP_URL="http://localhost:8000"

DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_NAME="company_marketplace"
DB_USER="root"
DB_PASS=""

MAX_UPLOAD_SIZE_MB=5
ALLOWED_EXTENSIONS="jpg,jpeg,png,webp"
```

---

## 5. Konfigurasi Base URL

Sesuaikan `APP_URL` di `.env` sesuai dengan lingkungan Anda:
- Jika menggunakan **PHP Built-in Server**: `http://localhost:8000`
- Jika menggunakan **XAMPP / htdocs**: `http://localhost/web-company-profile/public` atau `http://localhost/web-company-profile`
- Jika di **Domain Hosting / VPS**: `https://perusahaananda.com`

---

## 6. Konfigurasi Upload & Hak Akses Folder

Pastikan folder berikut memiliki izin tulis (*writable / 0755 atau 0775*):
- `public/uploads/` (serta seluruh subfolder `banners`, `categories`, `products`, `articles`, `galleries`, `settings`)
- `storage/logs/`

Di Linux / VPS:
```bash
chmod -R 755 public/uploads storage/logs
chown -R www-data:www-data public/uploads storage/logs
```

---

## 7. Konfigurasi Apache

Pastikan modul `mod_rewrite` aktif. File `.htaccess` utama dan `public/.htaccess` sudah disertakan langsung di dalam proyek.

### Contoh VirtualHost Apache (`httpd-vhosts.conf`):
```apache
<VirtualHost *:80>
    ServerName perusahaananda.local
    DocumentRoot "d:/web company profile/public"
    
    <Directory "d:/web company profile/public">
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "logs/perusahaan-error.log"
    CustomLog "logs/perusahaan-access.log" combined
</VirtualHost>
```

---

## 8. Konfigurasi Nginx

Jika menggunakan Nginx, arahkan `root` ke direktori `public` dan gunakan konfigurasi `try_files`:

```nginx
server {
    listen 80;
    server_name perusahaananda.com;
    root /var/www/web-company-profile/public;

    index index.php index.html;

    charset utf-8;

    # Blokir eksekusi skrip di folder uploads
    location ~* ^/uploads/.*.(php|phtml|php3|php4|php5|php7|php8|phar|sh|pl)$ {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000; # atau unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 9. Akun Default Administrator

Setelah import `database.sql`:
- **URL Admin**: `/admin/login` (Contoh: `http://localhost:8000/admin/login`)
- **Username**: `admin` *(atau email: `admin@solusitekno.co.id`)*
- **Password**: `Admin12345!`

> [!TIP]
> Segera ganti password default setelah berhasil login pertama kali melalui menu **Pengguna Admin** di dashboard.

---

## 10. Cara Menjalankan Aplikasi

### Cara Termudah (PHP Built-in Server):
Masuk ke root direktori proyek, lalu jalankan:
```bash
php -S localhost:8000 -t public
```
Buka browser Anda dan akses:
- Frontend Publik: `http://localhost:8000`
- Admin Panel: `http://localhost:8000/admin/login`

---

## 11. Cara Backup Database

### Melalui CLI (mysqldump):
```bash
mysqldump -u root -p company_marketplace > backup_company_marketplace_$(date +%Y%m%d).sql
```

### Melalui phpMyAdmin:
1. Buka database `company_marketplace`.
2. Klik tab **Export**.
3. Pilih metode **Quick** dan format **SQL**, lalu klik **Export**.

---

## 12. Troubleshooting

1. **Muncul pesan "Koneksi database gagal"**:
   - Pastikan service MySQL/MariaDB sudah aktif.
   - Periksa konfigurasi nama database, username, dan password di `.env`.

2. **Halaman 404 saat klik menu produk / artikel**:
   - Jika menggunakan Apache, pastikan `mod_rewrite` sudah aktif.
   - Jika menggunakan Nginx, pastikan `try_files $uri $uri/ /index.php?$query_string;` sudah terpasang.

3. **Gagal mengunggah foto**:
   - Periksa izin tulis (*write permissions*) pada direktori `public/uploads/`.
   - Pastikan ukuran file tidak melebihi `upload_max_filesize` di `php.ini` atau batas 5MB.
   - Format file yang didukung: JPG, JPEG, PNG, WEBP.

4. **Nomor WhatsApp tidak sesuai**:
   - Masuk ke Admin Panel -> **Profil Perusahaan**, ubah nomor WhatsApp pada kolom yang tersedia dan simpan.

---

## 13. Fitur Keamanan yang Diterapkan

- **Prepared Statements PDO**: Menjamin 100% perlindungan terhadap ancaman SQL Injection.
- **CSRF Token**: Dilengkapi token verifikasi pada setiap form sensitif di Admin Panel dan Checkout.
- **XSS Escaping**: Seluruh output dinamis dieksekusi melalui `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Proteksi Eksekusi Uploads**: Folder `uploads` dilengkapi file proteksi `.htaccess` yang melarang eksekusi skrip PHP atau CGI.
- **Session Hardening**: Menggunakan `cookie_httponly`, `cookie_samesite=Lax`, regenerasi session ID saat login, dan auto-timeout 2 jam.
- **Error Obfuscation**: Error SQL dan credential tidak pernah dibocorkan ke pengunjung publik (dicatat otomatis ke `storage/logs/app.log`).
