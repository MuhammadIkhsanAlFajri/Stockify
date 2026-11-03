<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stockify — Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f9fc;
            color: #172033;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 245px;
            background: #ffffff;
            border-right: 1px solid #e7ebf2;
            padding: 28px 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 23px;
            font-weight: 800;
            color: #2459d3;
            margin-bottom: 40px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: #eaf0ff;
        }

        .nav-label {
            margin: 24px 12px 10px;
            color: #98a2b3;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-link {
            display: block;
            padding: 12px 14px;
            margin: 5px 0;
            color: #667085;
            text-decoration: none;
            border-radius: 9px;
            font-size: 14px;
        }

        .nav-link.active {
            background: #edf2ff;
            color: #2459d3;
            font-weight: bold;
        }

        .main {
            flex: 1;
            min-width: 0;
            padding: 32px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 34px;
        }

        .eyebrow {
            color: #7d8798;
            font-size: 13px;
            margin-bottom: 8px;
        }

        h1 {
            margin: 0;
            font-size: 29px;
            letter-spacing: -0.7px;
        }

        .user {
            display: flex;
            align-items: center;
            gap: 11px;
            background: white;
            padding: 9px 14px;
            border: 1px solid #e7ebf2;
            border-radius: 12px;
            font-size: 13px;
        }

        .avatar {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #dfe8ff;
            color: #2459d3;
            font-weight: bold;
        }

        .welcome {
            padding: 25px 28px;
            border-radius: 16px;
            background: #2459d3;
            color: white;
            margin-bottom: 25px;
        }

        .welcome h2 {
            margin: 0 0 9px;
            font-size: 21px;
        }

        .welcome p {
            margin: 0;
            color: #e1e9ff;
            font-size: 14px;
            line-height: 1.7;
        }

        .section-title {
            margin: 30px 0 16px;
            font-size: 18px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .stat-card {
            background: white;
            padding: 21px;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #667085;
            font-size: 13px;
            gap: 8px;
        }

        .stat-icon {
            display: grid;
            place-items: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #edf2ff;
            color: #2459d3;
            font-size: 20px;
        }

        .stat-number {
            margin-top: 19px;
            font-size: 30px;
            font-weight: bold;
        }

        .stat-note {
            margin-top: 7px;
            color: #98a2b3;
            font-size: 12px;
        }

        .table-card {
            overflow: hidden;
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
        }

        .table-heading {
            padding: 20px;
            border-bottom: 1px solid #e7ebf2;
            font-weight: bold;
        }

        .empty {
            padding: 45px 20px;
            text-align: center;
            color: #98a2b3;
            font-size: 14px;
        }

        @media (max-width: 1000px) {
            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar {
                width: 205px;
            }

            .main {
                padding: 24px;
            }
        }

        @media (max-width: 600px) {
            .sidebar {
                display: none;
            }

            .main {
                padding: 20px 15px;
            }

            .topbar {
                align-items: flex-start;
            }

            h1 {
                font-size: 24px;
            }

            .user {
                padding: 7px;
            }

            .stats {
                gap: 10px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-number {
                font-size: 25px;
            }
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .notification-link {
            position: relative;
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 12px;
            text-decoration: none;
            font-size: 21px;
            color: #475467;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 19px;
            height: 19px;
            padding: 0 5px;
            display: grid;
            place-items: center;
            background: #dc2626;
            color: white;
            border: 2px solid white;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="layout">

        <aside>
            <div class="brand">
                <div class="brand-icon">▦</div>
                Stockify
            </div>

            <div class="nav-label">MENU UTAMA</div>

            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                ▦ &nbsp; Dashboard
            </a>

            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                href="{{ route('products.index') }}">
                ▤ &nbsp; Data Barang
            </a>

            <div class="nav-label">INVENTARIS</div>

            <a class="nav-link {{ request()->routeIs('stock-movements.create') && request('type') === 'in' ? 'active' : '' }}"
                href="{{ route('stock-movements.create', ['type' => 'in']) }}">
                ↓ &nbsp; Stok Masuk
            </a>

            <a class="nav-link {{ request()->routeIs('stock-movements.create') && request('type') === 'out' ? 'active' : '' }}"
                href="{{ route('stock-movements.create', ['type' => 'out']) }}">
                ↑ &nbsp; Stok Keluar
            </a>

            <a class="nav-link {{ request()->routeIs('stock-movements.index') ? 'active' : '' }}"
                href="{{ route('stock-movements.index') }}">
                ▧ &nbsp; Riwayat Stok
            </a>

            <a class="nav-link {{ request()->routeIs('low-stock.*') ? 'active' : '' }}"
                href="{{ route('low-stock.index') }}">
                ⚠ &nbsp; Peringatan Stok
            </a>

            <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
                href="{{ route('purchases.index') }}">
                🛒 &nbsp; Pembelian
            </a>

            <a href="{{ route('suppliers.index') }}"
                class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                <span>🏢</span>
                Supplier
            </a>

            <a class="nav-link {{ request()->routeIs('stock-opnames.*') ? 'active' : '' }}"
                href="{{ route('stock-opnames.index') }}">
                📋 &nbsp; Stock Opname
            </a>

            @if (auth()->user()->role === 'admin')
            <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                href="{{ route('categories.index') }}">
                ▦ &nbsp; Kategori
            </a>
            @endif

            <div class="nav-label">LAINNYA</div>

            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                href="{{ route('reports.index') }}">
                ▥ &nbsp; Laporan
            </a>


            @if (auth()->user()->role === 'admin')
            <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}"
                href="{{ route('activity-logs.index') }}">
                ▤ &nbsp; Log Aktivitas
            </a>
            @endif


            <a class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
                href="{{ route('settings.index') }}">
                ⚙ &nbsp; Pengaturan
            </a>

            <form method="POST" action="{{ route('logout') }}"
                style="margin-top: 20px;">
                @csrf

                <button type="submit"
                    class="nav-link"
                    style="width:100%; border:none; background:transparent; cursor:pointer; text-align:left; font-family:inherit;">
                    ↪ &nbsp; Logout
                </button>
            </form>

        </aside>



        <main class="main">
            <header class="topbar">
                <div>
                    <div class="eyebrow">Stockify / Dashboard</div>
                    <h1>Dashboard</h1>
                </div>

                <div class="topbar-actions">

                    <a href="{{ route('low-stock.index') }}"
                        class="notification-link"
                        title="Lihat peringatan stok">

                        🔔

                        @if (($outOfStock + $lowStock) > 0)
                        <span class="notification-badge">
                            {{ $outOfStock + $lowStock }}
                        </span>
                        @endif
                    </a>

                    <div class="user">
                        <div class="avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div>
                            <strong>{{ auth()->user()->name }}</strong>

                            <div class="eyebrow" style="margin: 4px 0 0;">
                                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <section class="welcome">
                <h2>Selamat datang di Stockify 👋</h2>
                <p>
                    Kelola persediaan barang dengan lebih mudah,
                    teratur, dan efisien dari satu tempat.
                </p>
            </section>

            <h2 class="section-title">Ringkasan Inventaris</h2>

            <section class="stats">
                <div class="stat-card">
                    <div class="stat-top">
                        Total Barang
                        <div class="stat-icon">▦</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalProducts) }}</div>
                    <div class="stat-note">Jenis produk terdaftar</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        Total Stok
                        <div class="stat-icon">▤</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalStock) }}</div>
                    <div class="stat-note">Jumlah unit seluruh barang</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        Stok Masuk
                        <div class="stat-icon">↓</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalStockIn) }}</div>
                    <div class="stat-note">Transaksi masuk</div>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        Stok Keluar
                        <div class="stat-icon">↑</div>
                    </div>
                    <div class="stat-number">{{ number_format($totalStockOut) }}</div>
                    <div class="stat-note">Transaksi keluar</div>
                </div>


                <a href="{{ route('low-stock.index') }}"
                    class="stat-card"
                    style="text-decoration:none; color:inherit; display:block; cursor:pointer;">

                    <div class="stat-top">
                        Stok Habis
                        <div class="stat-icon"
                            style="background:#fee2e2; color:#dc2626;">
                            !
                        </div>
                    </div>

                    <div class="stat-number" style="color:#dc2626;">
                        {{ number_format($outOfStock) }}
                    </div>

                    <div class="stat-note">
                        {{ $outOfStock > 0 ? 'Ada barang yang perlu diisi ulang' : 'Tidak ada barang yang stoknya habis' }}
                    </div>
                </a>


                <a href="{{ route('low-stock.index') }}"
                    class="stat-card"
                    style="text-decoration:none; color:inherit; display:block; cursor:pointer; transition:transform 0.2s, box-shadow 0.2s;"
                    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 5px 15px #00000012';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">

                    <div class="stat-top">
                        Stok Menipis
                        <div class="stat-icon"
                            style="background:#fee2e2; color:#dc2626;">
                            !
                        </div>
                    </div>

                    <div class="stat-number"
                        style="color: #dc2626;">
                        {{ number_format($lowStock) }}
                    </div>

                    <div class="stat-note">
                        {{ $lowStock > 0 ? 'Klik untuk memeriksa barang' : 'Semua stok dalam batas aman' }}
                    </div>
                </a>

            </section>

            <h2 class="section-title">Aktivitas Terbaru</h2>


            <section class="table-card">
                <div class="table-heading">Riwayat Pergerakan Stok</div>

                @if ($recentMovements->isEmpty())
                <div class="empty">
                    Belum ada aktivitas stok.<br>
                    Riwayat akan tampil setelah kamu mulai mengelola barang.
                </div>
                @else
                <div style="overflow-x: auto;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead>
                            <tr style="color:#667085; font-size:13px;">
                                <th style="padding:15px 20px;">Tanggal</th>
                                <th style="padding:15px 20px;">Produk</th>
                                <th style="padding:15px 20px;">Jenis</th>
                                <th style="padding:15px 20px;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentMovements as $movement)
                            <tr style="border-top:1px solid #e7ebf2;">
                                <td style="padding:15px 20px; white-space:nowrap;">
                                    {{ $movement->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td style="padding:15px 20px;">
                                    {{ $movement->product?->name ?? 'Barang telah dihapus' }}
                                </td>

                                <td style="padding:15px 20px; font-weight:bold;">
                                    @if ($movement->type === 'in')
                                    <span style="color: #15803d;">Stok Masuk</span>
                                    @else
                                    <span style="color: #dc2626;">Stok Keluar</span>
                                    @endif
                                </td>

                                <td style="padding:15px 20px;">
                                    {{ number_format($movement->quantity) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </section>

        </main>
    </div>
</body>

</html>