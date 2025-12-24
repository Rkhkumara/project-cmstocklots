# CM Stocklots - E-Commerce Web Application

Aplikasi e-commerce berbasis web untuk penjualan pakaian (fashion) yang dibangun menggunakan PHP Native dan MySQL. Sistem ini mencakup fitur lengkap untuk pelanggan (frontend) dan administrator (backend/dashboard).

## 📋 Fitur Aplikasi

### Halaman Pengunjung (User)

- **Katalog Produk:** Menampilkan produk dengan fitur pencarian, filter kategori, dan pengurutan harga/nama.
- **Manajemen Akun:** Registrasi, Login, Edit Profil, dan Ganti Password.
- **Keranjang Belanja:** Menambah produk, mengubah kuantitas, dan menghapus item.
- **Checkout & Pesanan:** Input alamat pengiriman otomatis (berdasarkan kecamatan dengan ongkir statis) dan pembuatan invoice.
- **Pembayaran Manual:** Metode transfer bank dengan fitur **unggah bukti pembayaran**.
- **Riwayat Pesanan:** Melacak status pesanan (Menunggu Pembayaran, Verifikasi, Lunas, Dikirim, Selesai, Ditolak).
- **Cetak Invoice:** Fitur cetak bukti pemesanan.

### Halaman Administrator (Admin)

- **Dashboard Admin:** Ringkasan navigasi.
- **Manajemen Produk:** Tambah, Edit, Hapus (Soft Delete/Nonaktifkan), dan Kelola Stok/Harga/Gambar.
- **Verifikasi Pembayaran:** Memeriksa bukti transfer user, menyetujui (stok berkurang otomatis), atau menolak pembayaran.
- **Manajemen Pesanan:** Update status pengiriman (Dikirim/Selesai) dan lihat detail pesanan.
- **Data Pengguna:** Melihat daftar pengguna terdaftar.

## 🛠️ Teknologi yang Digunakan

- **Bahasa Pemrograman:** PHP (Native/Procedural)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework UI:** Bootstrap 5 (CDN)
- **Icon:** Bootstrap Icons
- **Font:** Google Fonts (Montserrat & Cormorant Garamond)

## ⚙️ Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal (Localhost):

### 1. Persiapan Lingkungan

Pastikan Anda telah menginstal Web Server paket seperti **XAMPP**, **Laragon**, atau **MAMP**.

### 2. Setup Database

1.  Buka **phpMyAdmin** (biasanya di `http://localhost/phpmyadmin`).
2.  Buat database baru dengan nama `db_fashion`.
3.  Impor file `db_fashion.sql` yang disertakan dalam proyek ini ke dalam database tersebut.

### 3. Konfigurasi Koneksi

1.  Letakkan folder proyek ini di dalam direktori root server (misal: `htdocs` pada XAMPP).
2.  Buka file `includes/db.php`.
3.  Sesuaikan kredensial database jika konfigurasi server Anda berbeda (default XAMPP biasanya tidak perlu diubah):
    ```php
    $host = 'localhost';
    $user = 'root';      // Username database default
    $pass = '';          // Password database default (kosong)
    $db_name = 'db_fashion';
    ```

### 4. Konfigurasi Folder Upload

Pastikan folder berikut tersedia dan memiliki izin tulis (write permission) agar fitur upload gambar produk dan bukti pembayaran berfungsi:

- `cmclots/uploads/products/`
- `cmclots/uploads/proofs/`

## 📂 Struktur Direktori Utama

cmclots/
│
├── admin/ # Halaman & Logika Administrator
│ ├── \_footer_admin.php
│ ├── \_header_admin.php
│ ├── detail_pesanan.php
│ ├── edit_produk.php
│ ├── hapus_produk.php
│ ├── index.php # Dashboard Produk Admin
│ ├── pengguna.php
│ ├── pesanan.php
│ ├── proses_verifikasi.php
│ ├── tambah_produk.php
│ └── verifikasi_pembayaran.php
│
├── assets/ # File Statis (CSS/JS)
│ ├── css/
│ │ └── style.css
│ └── js/
│ └── main.js
│
├── includes/ # Komponen Umum
│ ├── db.php # Koneksi Database
│ ├── footer.php
│ └── header.php
│
├── uploads/ # Penyimpanan File
│ ├── products/ # Gambar Produk
│ │ ├── [file_gambar_produk.png/jpg]
│ │ └── ...
│ └── proofs/ # Bukti Pembayaran
│ ├── [file_bukti_transfer.png/jpg]
│ └── ...
│
├── about.php
├── akun.php
├── checkout.php
├── contact.php
├── db_fashion.sql # File Database SQL
├── edit_profil.php
├── ganti_password.php
├── index.php # Halaman Utama (Homepage)
├── invoice.php
├── keranjang.php
├── keranjang_action.php
├── login.php
├── logout.php
├── order_action.php
├── payment.php
├── produk.php
├── register.php
├── riwayat_pesanan.php
└── upload_payment_proof.php

## 🔐 Akun Administrator

Secara default, `db_fashion.sql` mungkin sudah memiliki akun admin. Namun, karena password di-hash menggunakan `password_hash()`, Anda tidak bisa membacanya langsung.

**Cara membuat Admin baru:**

1.  Buka halaman **Register** (`register.php`) di browser.
2.  Daftar akun baru.
3.  Buka **phpMyAdmin**, masuk ke tabel `users`.
4.  Cari user yang baru dibuat, lalu ubah kolom `role` dari `'user'` menjadi `'admin'`.
5.  Login kembali dengan akun tersebut untuk mengakses folder `/admin`.

## ⚠️ Catatan Penting

- **Keamanan:** Aplikasi ini menggunakan PHP Session dan Prepared Statements (MySQLi) untuk mencegah SQL Injection dasar. Namun, untuk penggunaan produksi (live server), disarankan menambah validasi CSRF dan mengamankan folder upload.
- **Ongkos Kirim:** Logika ongkos kirim saat ini _hardcoded_ berdasarkan kecamatan tertentu di file `order_action.php` dan `checkout.php`.

---

_Dibuat untuk keperluan Tugas/Proyek Pengembangan Web._
