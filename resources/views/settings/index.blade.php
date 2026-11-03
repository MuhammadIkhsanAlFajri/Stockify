<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Stockify</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-top: 24px;
        }

        .muted {
            color: #6b7280;
            line-height: 1.7;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
        }

        .item {
            padding: 16px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .item:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a>

        <h1>Pengaturan Stockify</h1>
        <p class="muted">Informasi dasar aplikasi inventaris kamu.</p>

        <div class="card">
            <div class="item">
                <strong>Nama Aplikasi</strong>
                <p class="muted">Stockify</p>
            </div>

            <div class="item">
                <strong>Versi Aplikasi</strong>
                <p class="muted">Versi pengembangan awal</p>
            </div>

            <div class="item">
                <strong>Database</strong>
                <p class="muted">Database inventaris yang digunakan oleh Laravel.</p>
            </div>

            <div class="item">
                <strong>Fitur yang tersedia</strong>
                <p class="muted">
                    Dashboard, data barang, pencatatan stok,
                    riwayat transaksi, dan laporan inventaris.
                </p>
            </div>
        </div>

        <p style="margin-top:24px">
            <a class="button" href="{{ route('products.index') }}">
                Kelola Data Barang
            </a>

            @if (auth()->user()->role === 'admin')
            <a href="{{ route('users.index') }}"
                style="display:inline-block; margin-left:10px; padding:14px 20px; background:#15803d; color:white; text-decoration:none; border-radius:10px;">
                Kelola Pengguna
            </a>

            <a href="{{ route('settings.backup') }}"
                style="display:inline-block; margin-left:10px; padding:14px 20px; background:#15803d; color:white; text-decoration:none; border-radius:10px;">
                Backup dan Restore Database
            </a>
            @endif

            <a href="{{ route('settings.password') }}"
                style="display:inline-block; margin-left:10px; padding:14px 20px; background:#15803d; color:white; text-decoration:none; border-radius:10px;">
                Ganti Password
            </a>
        </p>
    </div>
</body>

</html>