<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Opname - Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .back {
            display: inline-block;
            color: #2459d3;
            font-size: 14px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back:hover {
            text-decoration: underline;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            color: #1e293b;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .subtitle {
            color: #64748b;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2459d3;
            color: white;
        }

        .btn-primary:hover {
            background: #1948b5;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            overflow-x: auto;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #dcfce7;
            color: #166534;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th,
        td {
            padding: 15px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        th {
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            background: #f8fafc;
        }

        tr:hover td {
            background: #fafcff;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .positive {
            background: #dcfce7;
            color: #166534;
        }

        .negative {
            background: #fee2e2;
            color: #991b1b;
        }

        .neutral {
            background: #e2e8f0;
            color: #475569;
        }

        .detail-link {
            color: #2459d3;
            text-decoration: none;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 40px 15px;
            color: #64748b;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
            flex: 1;
            min-width: 160px;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: bold;
            color: #475569;
        }

        .filter-group input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-reset {
            background: #e2e8f0;
            color: #334155;
        }
    </style>
</head>

<body>

    <div class="container">

        <a href="{{ route('dashboard') }}" class="back">
            ← Kembali ke Dashboard
        </a>

        <div class="header">
            <div>
                <h1>Stock Opname</h1>
                <p class="subtitle">
                    Pemeriksaan dan penyesuaian stok fisik barang.
                </p>
            </div>

            <a href="{{ route('stock-opnames.create') }}"
                class="btn btn-primary">
                + Tambah Stock Opname
            </a>
        </div>

        @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
        @endif

        <form method="GET" action="{{ route('stock-opnames.index') }}"
            class="filter-form">

            <div class="filter-group">
                <label for="search">Cari Produk</label>
                <input
                    type="search"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nama atau kode barang...">
            </div>

            <div class="filter-group">
                <label for="date_from">Tanggal Awal</label>
                <input
                    type="date"
                    id="date_from"
                    name="date_from"
                    value="{{ request('date_from') }}">
            </div>

            <div class="filter-group">
                <label for="date_to">Tanggal Akhir</label>
                <input
                    type="date"
                    id="date_to"
                    name="date_to"
                    value="{{ request('date_to') }}">
            </div>

            <button type="submit" class="btn btn-primary">
                Filter
            </button>

            <a href="{{ route('stock-opnames.index') }}"
                class="btn btn-reset">
                Reset
            </a>

        </form>

        <div class="card">

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Stok Sistem</th>
                        <th>Stok Fisik</th>
                        <th>Selisih</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($opnames as $opname)
                    <tr>
                        <td>
                            {{ $opnames->firstItem() + $loop->index }}
                        </td>

                        <td>
                            {{ $opname->opname_date->format('d/m/Y') }}
                        </td>

                        <td>
                            <strong>
                                {{ $opname->product->name }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format($opname->system_stock, 0, ',', '.') }}
                        </td>

                        <td>
                            {{ number_format($opname->physical_stock, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($opname->difference > 0)
                            <span class="badge positive">
                                +{{ number_format($opname->difference, 0, ',', '.') }}
                            </span>
                            @elseif($opname->difference < 0)
                                <span class="badge negative">
                                {{ number_format($opname->difference, 0, ',', '.') }}
                                </span>
                                @else
                                <span class="badge neutral">
                                    0 (Sesuai)
                                </span>
                                @endif
                        </td>

                        <td>
                            {{ $opname->user->name ?? 'Pengguna tidak tersedia' }}
                        </td>

                        <td>
                            <a class="detail-link"
                                href="{{ route('stock-opnames.show', $opname->id) }}">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty">
                            Belum ada riwayat Stock Opname.
                            <br><br>
                            Silakan klik tombol Tambah Stock Opname.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $opnames->links() }}
            </div>

        </div>

    </div>

</body>

</html>