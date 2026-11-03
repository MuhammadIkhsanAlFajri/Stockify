<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris - Stockify</title>

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
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0 0 8px;
        }

        p {
            color: #6b7280;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .secondary {
            background: #e5e7eb;
            color: #1f2937;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat,
        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 22px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 14px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-top: 12px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            color: #6b7280;
            font-size: 13px;
        }

        .in {
            color: #15803d;
            font-weight: bold;
        }

        .out {
            color: #dc2626;
            font-weight: bold;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 800px) {
            body {
                padding: 18px;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 450px) {
            .stats {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .no-print,
            .pagination {
                display: none !important;
            }

            .stat,
            .card {
                break-inside: avoid;
            }
        }


        .print-only {
            display: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm;
            }

            body {
                padding: 0;
                background: white;
                color: #111827;
                font-family: Arial, sans-serif;
                font-size: 10pt;
            }

            .container {
                max-width: none;
                width: 100%;
                margin: 0;
            }

            .no-print,
            .pagination {
                display: none !important;
            }

            .print-only {
                display: block !important;
                margin-top: 10px;
                font-size: 10pt;
                color: #374151;
            }

            h1 {
                font-size: 20pt;
                margin-bottom: 6px;
            }

            h2 {
                font-size: 14pt;
                margin-top: 10px;
            }

            p {
                color: #374151;
            }

            .stats {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }

            .stat,
            .card {
                border: 1px solid #9ca3af;
                border-radius: 0;
                padding: 10px;
                box-shadow: none;
                break-inside: avoid;
            }

            .stat-number {
                font-size: 18pt;
            }

            .table-wrap {
                overflow: visible;
            }

            table {
                width: 100%;
                min-width: 0 !important;
                border-collapse: collapse;
                font-size: 9pt;
            }

            th,
            td {
                padding: 7px;
                border: 1px solid #9ca3af;
                color: #111827;
            }

            th {
                background: #e5e7eb !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            tr {
                break-inside: avoid;
            }

            .in,
            .out {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            #transaksi,
            #opname {
                break-inside: auto;
                margin-top: 15px;
            }

            a {
                color: #111827;
                text-decoration: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">

            <nav class="no-print report-navigation">
                <a href="#transaksi" class="button secondary">
                    📦 Laporan Transaksi Stok
                </a>

                <a href="#opname" class="button secondary">
                    📋 Laporan Stock Opname
                </a>
            </nav>
            <div>
                <h1>Laporan Inventaris Stockify</h1>
                <p>Ringkasan persediaan, transaksi stok, dan hasil pemeriksaan fisik.</p>

                <div class="print-only">
                    <p>
                        Tanggal Cetak:
                        {{ now()->format('d/m/Y H:i') }}
                    </p>

                    <p>
                        Periode:
                        {{ $filters['start_date'] ?? 'Semua tanggal' }}
                        s/d
                        {{ $filters['end_date'] ?? 'Semua tanggal' }}
                    </p>
                </div>
            </div>

            <div class="no-print">
                <a href="{{ route('dashboard') }}" class="button secondary">
                    ← Dashboard
                </a>
                <a class="button"
                    href="{{ route('reports.export', request()->only(['start_date', 'end_date', 'search'])) }}">
                    Ekspor CSV
                </a>
                <button class="button" onclick="window.print()">
                    Cetak Laporan
                </button>
            </div>
        </header>

        <section class="stats">
            <div class="stat">
                <div class="stat-label">Total Barang</div>
                <div class="stat-number">
                    {{ number_format($totalProducts) }}
                </div>
            </div>

            <div class="stat">
                <div class="stat-label">Total Stok Saat Ini</div>
                <div class="stat-number">
                    {{ number_format($totalStock) }}
                </div>
            </div>

            <div class="stat">
                <div class="stat-label">Total Stok Masuk</div>
                <div class="stat-number">
                    {{ number_format($totalStockIn) }}
                </div>
            </div>

            <div class="stat">
                <div class="stat-label">Total Stok Keluar</div>
                <div class="stat-number">
                    {{ number_format($totalStockOut) }}
                </div>
            </div>
        </section>


        <form method="GET" action="{{ route('reports.index') }}"
            class="card no-print" style="margin-bottom:24px;">

            <h2>Filter Laporan</h2>

            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
                <div>
                    <label for="start_date">Dari Tanggal</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ $filters['start_date'] ?? '' }}"
                        style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                </div>

                <div>
                    <label for="end_date">Sampai Tanggal</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ $filters['end_date'] ?? '' }}"
                        style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                </div>

                <div>
                    <label for="search">Nama atau Kode Produk</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Contoh: Keyboard atau BRG-001"
                        style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:18px;">
                <button type="submit" class="button">Terapkan Filter</button>
                <a href="{{ route('reports.index') }}"
                    class="button secondary">Reset</a>
            </div>
        </form>

        <section class="card" id="transaksi">
            <h2>Riwayat Transaksi Stok</h2>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Jenis Transaksi</th>
                            <th>Jumlah</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($movements as $movement)
                        <tr>
                            <td>
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $movement->product->code }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>
                                @if ($movement->type === 'in')
                                <span class="in">Stok Masuk</span>
                                @else
                                <span class="out">Stok Keluar</span>
                                @endif
                            </td>
                            <td>{{ number_format($movement->quantity) }}</td>
                            <td>{{ $movement->note ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center">
                                Belum ada transaksi stok.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $movements->links() }}
            </div>


            <!-- LAPORAN STOCK OPNAME -->
            <section style="margin-top:30px;">
                <h1>Laporan Stock Opname</h1>
                <p>Ringkasan hasil pemeriksaan dan penyesuaian stok fisik.</p>
            </section>

            <!-- Ringkasan Opname -->
            <section class="stats">
                <div class="stat">
                    <div class="stat-label">Total Pemeriksaan</div>
                    <div class="stat-number">
                        {{ number_format($totalOpnames) }}
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-label">Total Selisih Bersih</div>
                    <div class="stat-number">
                        {{ number_format($totalDifference) }}
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-label">Total Kelebihan Stok</div>
                    <div class="stat-number in">
                        {{ number_format($totalIncrease) }}
                    </div>
                </div>

                <div class="stat">
                    <div class="stat-label">Total Kekurangan Stok</div>
                    <div class="stat-number out">
                        {{ number_format($totalDecrease) }}
                    </div>
                </div>
            </section>

            <!-- Filter Opname -->
            <form method="GET" action="{{ route('reports.index') }}"
                class="card no-print" style="margin-bottom:24px;">

                <h2>Filter Stock Opname</h2>

                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
                    <div>
                        <label for="opname_start_date">Dari Tanggal</label>
                        <input type="date"
                            id="opname_start_date"
                            name="start_date"
                            value="{{ $filters['start_date'] ?? '' }}"
                            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                    </div>

                    <div>
                        <label for="opname_end_date">Sampai Tanggal</label>
                        <input type="date"
                            id="opname_end_date"
                            name="end_date"
                            value="{{ $filters['end_date'] ?? '' }}"
                            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                    </div>

                    <div>
                        <label for="opname_search">Nama atau Kode Produk</label>
                        <input type="text"
                            id="opname_search"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari produk..."
                            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:18px;">
                    <button type="submit" class="button">Terapkan Filter</button>
                    <a href="{{ route('reports.index') }}" class="button secondary">
                        Reset
                    </a>
                    <a class="button"
                        href="{{ route('reports.opname.export', request()->only(['start_date', 'end_date', 'search'])) }}">
                        Ekspor Opname CSV
                    </a>
                </div>
            </form>

            <!-- Tabel Opname -->
            <section class="card" id="opname" style="margin-top:24px;">
                <h2>Riwayat Stock Opname</h2>

                <div class="table-wrap">
                    <table style="min-width:850px;">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stok Sistem</th>
                                <th>Stok Fisik</th>
                                <th>Selisih</th>
                                <th>Petugas</th>
                                <th>Catatan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($opnames as $opname)
                            <tr>
                                <td>
                                    {{ $opname->opname_date?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td>{{ $opname->product?->code ?? '-' }}</td>

                                <td>
                                    {{ $opname->product?->name ?? 'Produk telah dihapus' }}
                                </td>

                                <td>{{ number_format($opname->system_stock) }}</td>

                                <td>{{ number_format($opname->physical_stock) }}</td>

                                <td>
                                    @if ($opname->difference > 0)
                                    <span class="in">
                                        +{{ number_format($opname->difference) }}
                                    </span>
                                    @elseif ($opname->difference < 0)
                                        <span class="out">
                                        {{ number_format($opname->difference) }}
                                        </span>
                                        @else
                                        <span>0</span>
                                        @endif
                                </td>

                                <td>{{ $opname->user?->name ?? '-' }}</td>

                                <td>{{ $opname->notes ?: '-' }}</td>

                                <td>
                                    <a href="{{ route('stock-opnames.show', $opname->id) }}"
                                        class="button secondary">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align:center">
                                    Belum ada data stock opname.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{ $opnames->links() }}
                </div>
            </section>

        </section>
    </div>
</body>

</html>