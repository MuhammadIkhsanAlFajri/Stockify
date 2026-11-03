<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Stok - Stockify</title>

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
            gap: 16px;
            flex-wrap: wrap;
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
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px #00000008;
            overflow-x: auto;
            margin-bottom: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(150px, 1fr));
            gap: 16px;
            align-items: end;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .secondary {
            background: #e5e7eb;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
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
            background: #f9fafb;
        }

        td {
            font-size: 14px;
        }

        .in {
            color: #15803d;
            font-weight: bold;
        }

        .out {
            color: #dc2626;
            font-weight: bold;
        }

        .success {
            padding: 12px;
            margin-bottom: 16px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 800px) {
            body {
                padding: 16px;
            }

            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .card {
                padding: 16px;
            }
        }

        @media (max-width: 480px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Riwayat Stok</h1>
                <p>Catatan semua transaksi stok produk.</p>
            </div>

            <div>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                &nbsp;
                <a class="button" href="{{ route('stock-movements.create') }}">
                    + Catat Stok
                </a>
            </div>
        </div>

        @if (session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h3 style="margin-top:0">Cari dan Filter Transaksi</h3>

            <form method="GET" action="{{ route('stock-movements.index') }}">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="search">Kode atau Nama Produk</label>
                        <input type="text" id="search" name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari produk...">
                    </div>

                    <div class="form-group">
                        <label for="type">Jenis Transaksi</label>
                        <select id="type" name="type">
                            <option value="">Semua Transaksi</option>
                            <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>Stok Masuk</option>
                            <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Stok Keluar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date"
                            value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="button" type="submit">Terapkan Filter</button>
                    <a class="button secondary" href="{{ route('stock-movements.index') }}">Reset Filter</a>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-top:0">Daftar Transaksi</h3>

            <p>Menampilkan {{ $movements->count() }} transaksi pada halaman ini.</p>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $movement->product?->code ?? '-' }}</td>
                        <td>{{ $movement->product?->name ?? 'Produk telah dihapus' }}</td>
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
                        <td colspan="6" style="text-align:center;padding:32px">
                            Tidak ada transaksi yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</body>

</html>