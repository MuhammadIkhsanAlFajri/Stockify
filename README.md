# Stockify – Inventory Management System

**Stockify** adalah aplikasi manajemen inventaris berbasis web yang dibangun menggunakan Laravel. Aplikasi ini dirancang untuk membantu pengelolaan data barang, pemantauan stok, transaksi barang masuk dan keluar, serta pembuatan laporan inventaris secara lebih terstruktur.

## ✨ Fitur Utama

### 1. Dashboard
- Ringkasan jumlah produk dan kategori.
- Informasi stok barang.
- Pemantauan barang dengan stok menipis atau habis.

### 2. Manajemen Produk
- Menambahkan, melihat, mengubah, dan menghapus data produk.
- Mengelola kategori produk.
- Menyimpan informasi lokasi penyimpanan barang.
- Mengatur stok minimum produk.

### 3. Manajemen Supplier
- Menambahkan dan mengelola data supplier.
- Menyimpan informasi perusahaan, kontak, alamat, dan catatan supplier.

### 4. Pembelian dan Stok Masuk
- Mencatat transaksi pembelian barang.
- Menghubungkan pembelian dengan supplier.
- Menyimpan detail barang yang dibeli.
- Memperbarui stok produk berdasarkan transaksi pembelian.

### 5. Stok Keluar
- Mencatat pengeluaran barang.
- Memperbarui jumlah stok secara otomatis.
- Menyimpan riwayat pergerakan stok.

### 6. Stock Opname
- Membandingkan stok sistem dengan stok fisik.
- Menghitung selisih stok secara otomatis.
- Menyesuaikan stok produk berdasarkan hasil pemeriksaan.
- Menyimpan riwayat stock opname.

### 7. Laporan
- Menampilkan laporan pergerakan stok.
- Menampilkan laporan stock opname.
- Menyediakan filter berdasarkan tanggal dan pencarian.
- Mengekspor laporan ke format CSV.
- Mendukung pencetakan laporan.

### 8. Manajemen Pengguna
- Login dan logout.
- Pengaturan hak akses berdasarkan role Admin dan Staff.
- Pengelolaan akun pengguna.
- Mengubah password.

### 9. Aktivitas dan Backup
- Mencatat aktivitas pengguna.
- Menyediakan fitur backup dan restore database.

## 🛠️ Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| Laravel | Framework backend |
| PHP | Bahasa pemrograman backend |
| MySQL / MariaDB | Database |
| HTML | Struktur halaman |
| CSS | Tampilan antarmuka |
| JavaScript | Interaksi halaman |
| Vite | Pengelolaan aset frontend |

## 📋 Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat sudah memiliki:

- PHP sesuai persyaratan versi Laravel yang digunakan.
- Composer.
- MySQL atau MariaDB.
- Node.js dan NPM.
- Git.

## 🚀 Cara Instalasi

**1. Clone repository**

```bash
git clone https://github.com/MuhammadIkhsanAlFajri/Stockify.git
```

**2. Masuk ke direktori project**

```bash
cd Stockify
```

**3. Install dependency PHP**

```bash
composer install
```

**4. Buat file environment**

Windows:

```powershell
Copy-Item .env.example .env
```

Linux / macOS:

```bash
cp .env.example .env
```

**5. Generate application key**

```bash
php artisan key:generate
```

**6. Konfigurasi database**

Buat database baru, kemudian sesuaikan konfigurasi pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockify_db
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, password, dan port dengan konfigurasi lokal masing-masing.

**7. Jalankan migration**

```bash
php artisan migrate
```

**8. Install dependency frontend**

```bash
npm install
```

**9. Build aset frontend**

```bash
npm run build
```

**10. Jalankan aplikasi**

```bash
php artisan serve
```

Buka aplikasi melalui alamat:

```text
http://127.0.0.1:8000
```

## 🔐 Catatan Keamanan

- Jangan mengunggah file `.env` ke repository publik.
- Jangan membagikan password database atau application key.
- Pastikan konfigurasi database sudah benar sebelum menjalankan migration.

## 📌 Status Pengembangan

Stockify dikembangkan secara bertahap. Fitur utama pengelolaan inventaris, transaksi stok, stock opname, laporan, serta manajemen pengguna telah dibuat.

## 👨‍💻 Developer

**Muhammad Ikhsan Al Fajri**

GitHub: [@MuhammadIkhsanAlFajri](https://github.com/MuhammadIkhsanAlFajri)

---

<p align="center">
  Made with ❤️ using Laravel
</p>
