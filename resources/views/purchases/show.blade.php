<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pembelian — Stockify</title>

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
            max-width: 1100px;
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

        .card {
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 20px;
        }

        h2 {
            margin: 0 0 20px;
            font-size: 19px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .info-label {
            color: #7d8798;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
            font-size: 14px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #edf0f5;
        }

        th {
            background: #fafbfe;
            color: #667085;
            font-weight: 600;
        }

        .total {
            display: flex;
            justify-content: flex-end;
            margin-top: 22px;
            font-size: 22px;
            font-weight: bold;
        }

        .button {
            display: inline-block;
            padding: 12px 17px;
            border-radius: 9px;
            background: #2459d3;
            color: white;
            font-size: 14px;
        }

        .notes {
            color: #475467;
            line-height: 1.6;
            white-space: pre-line;
        }

        @media (max-width: 600px) {
            main {
                padding: 24px 12px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 17px;
            }

            .card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('purchases.index') }}">
            ← Kembali ke Riwayat Pembelian
        </a>

        <div class="heading">
            <h1>Detail Pembelian</h1>
            <div class="muted">
                Informasi lengkap transaksi pembelian barang.
            </div>
        </div>

        <section class="card">
            <h2>Informasi Pembelian</h2>

            <div class="info-grid">
                <div>
                    <div class="info-label">Nomor Faktur</div>
                    <div class="info-value">
                        {{ $purchase->invoice_number }}
                    </div>
                </div>

                <div>
                    <div class="info-label">Tanggal Pembelian</div>
                    <div class="info-value">
                        {{ $purchase->purchase_date->format('d-m-Y') }}
                    </div>
                </div>

                <div>
                    <div class="info-label">Supplier</div>
                    <div class="info-value">
                        {{ $purchase->supplier->name }}
                    </div>
                </div>

                <div>
                    <div class="info-label">Perusahaan</div>
                    <div class="info-value">
                        {{ $purchase->supplier->company ?: '-' }}
                    </div>
                </div>

                <div>
                    <div class="info-label">Petugas</div>
                    <div class="info-value">
                        {{ $purchase->user->name }}
                    </div>
                </div>

                <div>
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        {{ ucfirst($purchase->status) }}
                    </div>
                </div>
            </div>
        </section>

        <section class="card">
            <h2>Daftar Barang</h2>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Beli/Unit</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $item->product->code ?: '-' }}
                            </td>

                            <td>
                                {{ $item->product->name }}
                            </td>

                            <td>
                                {{ number_format($item->quantity, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format((float) $item->unit_cost, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="total">
                Total: Rp {{ number_format((float) $purchase->total_amount, 0, ',', '.') }}
            </div>
        </section>

        @if($purchase->notes)
        <section class="card">
            <h2>Catatan Pembelian</h2>
            <div class="notes">{{ $purchase->notes }}</div>
        </section>
        @endif

        <a class="button" href="{{ route('purchases.index') }}">
            Kembali ke Riwayat
        </a>
    </main>
</body>

</html>