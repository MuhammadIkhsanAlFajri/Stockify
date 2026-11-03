# 📦 Stockify
### Inventory Management System

<p align="center">
  A web-based inventory management system built with Laravel.
  <br />
  Manage products, monitor stock levels, record transactions, and organize inventory reports in one place.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-Framework-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/Status-Development-blue?style=for-the-badge" alt="Development" />
</p>

---

## 📖 About The Project

**Stockify** merupakan aplikasi manajemen inventaris berbasis web yang dikembangkan menggunakan framework Laravel.

Aplikasi ini membantu proses pengelolaan persediaan barang, mulai dari pencatatan produk, pengelolaan supplier, transaksi pembelian, pemantauan stok, hingga pembuatan laporan inventaris.

Stockify dirancang untuk membuat pengelolaan inventaris menjadi lebih terorganisir, efisien, dan mudah dipantau.

## 📑 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Project Structure](#-project-structure)
- [Security Notes](#-security-notes)
- [Developer](#-developer)

## ✨ Features

| Feature | Description |
|---|---|
| 📊 **Dashboard** | Menampilkan ringkasan inventaris, informasi stok, dan indikator stok menipis atau habis. |
| 📦 **Product Management** | Mengelola data produk, kategori, lokasi penyimpanan, dan stok minimum. |
| 🏢 **Supplier Management** | Mengelola informasi supplier, perusahaan, kontak, dan alamat. |
| 🛒 **Purchase Management** | Mencatat transaksi pembelian dan memperbarui stok berdasarkan barang yang diterima. |
| 📤 **Stock Out** | Mencatat pengeluaran barang dan memperbarui jumlah stok. |
| 🔍 **Stock Opname** | Membandingkan stok sistem dengan stok fisik serta mencatat selisihnya. |
| 📈 **Reports** | Menampilkan laporan pergerakan stok dan stock opname, dengan filter, ekspor CSV, dan fitur cetak. |
| 👥 **User Management** | Mengelola akun pengguna dengan hak akses Admin dan Staff. |
| 📝 **Activity Logs** | Mencatat aktivitas pengguna di dalam aplikasi. |
| 💾 **Database Backup** | Menyediakan fitur backup dan restore database. |

## 🛠️ Tech Stack

Teknologi utama yang digunakan dalam pengembangan Stockify:

| Technology | Purpose |
|---|---|
| **Laravel** | Backend framework |
| **PHP** | Server-side programming |
| **MySQL / MariaDB** | Database management |
| **HTML5** | Page structure |
| **CSS3** | User interface styling |
| **JavaScript** | Frontend interaction |
| **Vite** | Frontend asset bundling |

## 📋 Requirements

Pastikan perangkat telah memiliki beberapa kebutuhan berikut:

- PHP yang kompatibel dengan versi Laravel proyek.
- Composer.
- MySQL atau MariaDB.
- Node.js dan NPM.
- Git.

## 🚀 Installation

Ikuti langkah-langkah berikut untuk menjalankan Stockify secara lokal.

### 1. Clone Repository

```bash
git clone https://github.com/MuhammadIkhsanAlFajri/Stockify.git
```

### 2. Navigate to Project Directory

```bash
cd Stockify
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Create Environment File

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

**Linux / macOS:**

```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Buat database dengan nama `stockify_db`, kemudian sesuaikan konfigurasi pada file `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockify_db
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan port, username, dan password dengan konfigurasi database lokal.

### 7. Run Database Migrations

```bash
php artisan migrate
```

### 8. Install Frontend Dependencies

```bash
npm install
```

### 9. Build Frontend Assets

```bash
npm run build
```

### 10. Start Development Server

```bash
php artisan serve
```

Buka browser dan akses:

```text
http://127.0.0.1:8000
```

## 📁 Project Structure

Struktur direktori utama Laravel yang digunakan:

```text
Stockify/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Models/
│   └── Providers/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   └── web.php
├── storage/
├── tests/
├── .env.example
├── artisan
├── composer.json
├── package.json
└── README.md
```

## 🔐 Security Notes

- Jangan mengunggah file `.env` ke repository publik.
- Jangan membagikan kredensial database maupun application key.
- Gunakan konfigurasi database sendiri pada lingkungan lokal.
- Pastikan konfigurasi keamanan sudah sesuai sebelum aplikasi digunakan di lingkungan produksi.

## 📌 Project Status

**Stockify is under development.**

Aplikasi dikembangkan secara bertahap dengan fokus pada pengelolaan inventaris, transaksi stok, stock opname, pelaporan, dan manajemen pengguna.

## 👨‍💻 Developer

**Muhammad Ikhsan Al Fajri**

- GitHub: [@MuhammadIkhsanAlFajri](https://github.com/MuhammadIkhsanAlFajri)
- Repository: [Stockify](https://github.com/MuhammadIkhsanAlFajri/Stockify)

---

<p align="center">
  <b>Stockify – Simplify Your Inventory Management.</b>
  <br />
  Made with ❤️ using Laravel.
</p>
