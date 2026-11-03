<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stock Opname - Stockify</title>

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
            min-width: 850px;
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

        .neutral {
            color: #6b7280;
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
    </style>
</head>

<body>
    <div class="container">

        <header class="header">
            <div>
                <h1>Laporan Stock Opname</h1>
                <p>Ringkasan hasil pemeriksaan dan penyesuaian stok fisik.</p>
            </div>

            <div class="no-print">
                <a href="{{ route('dashboard') }}" class="button secondary">
                    ← Dashboard
                </a>

                <a class="button"
                    href="{{ route('reports.stock-opname.export', request()->only(['start_date', 'end_date', 'search'])) }}">
                    Ekspor CSV
                </a>

                <button class="button" onclick="window.print()">
                    Cetak Laporan
                </button>
            </div>
        </header>

        <section class="stats">
            <div class="stat">
                <div class="stat-label">Total Pemeriksaan</div>
                <div class="stat-number">{{ number_format($totalOpnames) }}</div>
            </div>

            <div class="stat">
                <div class="stat-label">Total Selisih Bersih</div>
                <div class="stat-number">{{ number_format($totalDifference) }}</div>
            </div>

            <div class="stat">
                <div class="stat-label">Total Kelebihan Stok</div>
                <div class="stat-number in">{{ number_format($totalIncrease) }}</div>
            </div>

            <div class="stat">
                <div class="stat-label">Total Kekurangan Stok</div>
                <div class="stat-number out">{{ number_format($totalDecrease) }}</div>
            </div>
        </section>

        <form method="GET" action="{{ route('reports.stock-opname') }}"
            class="card no-print" style="margin-bottom:24px;">

            <h2>Filter Laporan</h2>

            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px;">
                <div>
                    <label for="start_date">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ $filters['start_date'] ?? '' }}"
                        style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                </div>

                <div>
                    <label for="end_date">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date"
                        value="{{ $filters['end_date'] ?? '' }}"
                        style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                </div>

                <div>
                    <label for="search">Nama atau Kode Produk</label>
                    <input type="text" id="search" name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Contoh: Keyboard atau BRG-001"
                        style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:18px;">
                <button type="submit" class="button">Terapkan Filter</button>
                <a href="{{ route('reports.stock-opname') }}" class="button secondary">Reset</a>
            </div>
        </form>

        <section class="card">
            <h2>Riwayat Stock Opname</h2>

            <div class="table-wrap">
                <table>
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
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($opnames as $opname)
                        <tr>
                            <td>{{ $opname->opname_date?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $opname->product?->code ?? '-' }}</td>
                            <td>{{ $opname->product?->name ?? 'Produk telah dihapus' }}</td>
                            <td>{{ number_format($opname->system_stock) }}</td>
                            <td>{{ number_format($opname->physical_stock) }}</td>
                            <td>
                                @if ($opname->difference > 0)
                                <span class="in">+{{ number_format($opname->difference) }}</span>
                                @elseif ($opname->difference < 0)
                                    <span class="out">{{ number_format($opname->difference) }}</span>
                                    @else
                                    <span class="neutral">0</span>
                                    @endif
                            </td>
                            <td>{{ $opname->user?->name ?? '-' }}</td>
                            <td>{{ $opname->notes ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center">
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

    </div>
</body>

</html>