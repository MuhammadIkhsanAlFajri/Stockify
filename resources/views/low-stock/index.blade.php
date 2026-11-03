<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Stok - Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, Helvetica, sans-serif;
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
            font-size: 14px;
        }

        .button-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .summary-card {
            padding: 22px;
            border-radius: 12px;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .summary-card.danger {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .summary-card.warning {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .summary-label {
            font-size: 14px;
            color: #6b7280;
        }

        .summary-number {
            margin-top: 8px;
            font-size: 32px;
            font-weight: bold;
        }

        .danger-text {
            color: #b91c1c;
        }

        .warning-text {
            color: #c2410c;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px #00000008;
            overflow-x: auto;
            margin-bottom: 24px;
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
            background: #f9fafb;
            font-size: 13px;
        }

        td {
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-danger {
            color: #b91c1c;
            background: #fee2e2;
        }

        .badge-warning {
            color: #9a3412;
            background: #ffedd5;
        }

        .empty {
            padding: 28px;
            text-align: center;
            color: #15803d;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 650px) {
            body {
                padding: 16px;
            }

            .summary-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <div>
                <h1>⚠️ Peringatan Stok</h1>
                <p>Pantau barang yang habis atau mendekati batas minimum.</p>
            </div>

            <div>
                <a class="button button-secondary" href="{{ route('dashboard') }}">
                    Dashboard
                </a>
                <a class="button" href="{{ route('products.index') }}">
                    Data Barang
                </a>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card danger">
                <div class="summary-label">Barang Stok Habis</div>
                <div class="summary-number danger-text">
                    {{ number_format($totalOutOfStock) }}
                </div>
            </div>

            <div class="summary-card warning">
                <div class="summary-label">Barang Stok Menipis</div>
                <div class="summary-number warning-text">
                    {{ number_format($totalLowStock) }}
                </div>
            </div>
        </div>

        <div class="card">
            <h2 class="danger-text" style="margin-top:0">
                Barang Stok Habis
            </h2>
            <p>Barang yang stoknya sudah mencapai nol.</p>

            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Batas Minimum</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($outOfStockProducts as $product)
                    <tr>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? $product->category ?? '-' }}</td>
                        <td class="danger-text">
                            <strong>0 {{ $product->unit }}</strong>
                        </td>
                        <td>{{ number_format($product->minimum_stock) }} {{ $product->unit }}</td>
                        <td><span class="badge badge-danger">Stok Habis</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty">
                            Tidak ada barang dengan stok habis.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $outOfStockProducts->links() }}
            </div>
        </div>

        <div class="card">
            <h2 class="warning-text" style="margin-top:0">
                Barang Stok Menipis
            </h2>
            <p>Stok masih tersedia, tetapi sudah mencapai atau di bawah batas minimum.</p>

            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok Saat Ini</th>
                        <th>Batas Minimum</th>
                        <th>Kekurangan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockProducts as $product)
                    <tr>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category?->name ?? $product->category ?? '-' }}</td>
                        <td>{{ number_format($product->stock) }} {{ $product->unit }}</td>
                        <td>{{ number_format($product->minimum_stock) }} {{ $product->unit }}</td>
                        <td>{{ number_format(max(0, $product->minimum_stock - $product->stock)) }} {{ $product->unit }}</td>
                        <td><span class="badge badge-warning">Stok Menipis</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty">
                            Tidak ada barang dengan stok menipis.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">
                {{ $lowStockProducts->links() }}
            </div>
        </div>

    </div>
</body>

</html>