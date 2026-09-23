# Aturan Proyek: Proteksi Database & Menjalankan Aplikasi

## 1. Aturan Menjalankan Aplikasi (Run Server)
- **DILARANG MEMBUAT DATABASE BARU ATAU MERESET DATABASE**:
  Ketika ada perintah atau kebutuhan untuk menjalankan aplikasi (seperti "jalankan aplikasi", start server, run local web server, testing, dll.):
  - **JANGAN** membuat database baru.
  - **JANGAN** menjalankan script installer / migration / seeding (`database/install.php`).
  - **JANGAN** mereset atau mengosongkan tabel dan data pengaturan (`settings`, `products`, `articles`, `teams`, `galleries`, dll.).
  - **JANGAN** menimpa file `.env` yang sudah ada dengan konfigurasi kosong.

- **Perintah Resmi Menjalankan Web Server**:
  Untuk menjalankan aplikasi secara lokal, HANYA gunakan perintah PHP built-in web server:
  ```bash
  php -S localhost:8000 -t public public/index.php
  ```
  atau jalankan file:
  ```bash
  run_server.bat
  ```
  Server langsung menggunakan database MySQL yang sudah aktif dan data/pengaturan yang sudah ada tanpa menyentuh struktur atau isi database.

## 2. Proteksi Pengaturan & Data Website
- Pengaturan yang sudah diubah/disimpan oleh pengguna melalui Admin Panel (profil perusahaan, kontak, galeri, artikel, tim kepemimpinan, dll.) adalah data persisten yang wajib dipertahankan.
- Jangan pernah menjalankan perintah `DROP TABLE`, `TRUNCATE`, atau mengeksekusi `database/database.sql` kecuali pengguna secara eksplisit dan spesifik memintanya.
