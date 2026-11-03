<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pembelian — Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            color: #172033;
        }

        main {
            max-width: 1200px;
            margin: auto;
            padding: 35px 24px;
        }

        a {
            text-decoration: none;
        }

        .back {
            color: #2459d3;
            font-size: 14px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin: 25px 0;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 29px;
        }

        .muted {
            color: #7d8798;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 12px 17px;
            border: 0;
            border-radius: 9px;
            background: #2459d3;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .card {
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
            overflow: hidden;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border-bottom: 1px solid #e7ebf2;
        }

        input {
            width: 280px;
            max-width: 100%;
            padding: 11px 13px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            font-size: 14px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
            font-size: 13px;
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #edf0f5;
        }

        th {
            background: #fafbfe;
            color: #667085;
            font-weight: 600;
        }

        .detail {
            color: #2459d3;
            font-weight: 600;
        }

        .alert {
            margin-bottom: 18px;
            padding: 13px 16px;
            border-radius: 9px;
        }

        .success {
            background: #e8f7ee;
            color: #217849;
        }

        .error {
            background: #fff0e6;
            color: #b45309;
        }

        .empty {
            padding: 50px 20px;
            text-align: center;
            color: #7d8798;
        }

        .pagination {
            padding: 18px;
        }

        .pagination a,
        .pagination span {
            margin-right: 8px;
        }

        @media (max-width: 600px) {
            main {
                padding: 24px 12px;
            }

            .heading,
            .toolbar {
                align-items: stretch;
                flex-direction: column;
            }

            input {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('dashboard') }}">
            ← Kembali ke Dashboard
        </a>

        <div class="heading">
            <div>
                <h1>Riwayat Pembelian</h1>
                <div class="muted">
                    Catatan pembelian barang dari pemasok dan total biayanya.
                </div>
            </div>

            <a class="button" href="{{ route('purchases.create') }}">
                + Pembelian Baru
            </a>
        </div>

        @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
        @endif

        <section class="card">
            <div class="toolbar">
                <strong>
                    Daftar Pembelian ({{ $purchases->total() }})
                </strong>

                <form method="GET" action="{{ route('purchases.index') }}">
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nomor faktur atau supplier...">

                    <button class="button" type="submit">Cari</button>

                    @if(request('search'))
                    <a class="muted" href="{{ route('purchases.index') }}">
                        Reset
                    </a>
                    @endif
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Faktur</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Petugas</th>
                            <th>Total Pembelian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($purchases as $purchase)
                        <tr>
                            <td>
                                {{ $purchases->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <strong>{{ $purchase->invoice_number }}</strong>
                            </td>
                            <td>
                                {{ $purchase->purchase_date->format('d-m-Y') }}
                            </td>
                            <td>{{ $purchase->supplier->name }}</td>
                            <td>{{ $purchase->user->name }}</td>
                            <td>
                                <strong>
                                    Rp {{ number_format((float) $purchase->total_amount, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <a class="detail"
                                    href="{{ route('purchases.show', $purchase) }}">
                                    Lihat Detail →
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty">
                                    <h3>Belum ada pembelian</h3>
                                    <p>
                                        Catat pembelian pertama untuk mulai
                                        menambah stok dari supplier.
                                    </p>
                                    <a class="button"
                                        href="{{ route('purchases.create') }}">
                                        + Pembelian Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $purchases->links() }}
            </div>
        </section>
    </main>
</body>

</html>