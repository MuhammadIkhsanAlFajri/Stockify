<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Stock Opname — Stockify</title>

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
            max-width: 900px;
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

        h1 {
            margin: 25px 0 8px;
            font-size: 29px;
        }

        .muted {
            color: #7d8798;
            font-size: 14px;
        }

        .card {
            margin-top: 25px;
            padding: 25px;
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .item {
            padding-bottom: 15px;
            border-bottom: 1px solid #edf0f5;
        }

        .label {
            display: block;
            margin-bottom: 8px;
            color: #7d8798;
            font-size: 13px;
        }

        .value {
            font-size: 16px;
            font-weight: 600;
        }

        .difference {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .positive {
            background: #e8f7ee;
            color: #217849;
        }

        .negative {
            background: #fde8e8;
            color: #c0392b;
        }

        .neutral {
            background: #edf0f5;
            color: #667085;
        }

        .notes {
            margin-top: 22px;
            padding: 16px;
            background: #f7f9fc;
            border-radius: 9px;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 17px;
            border-radius: 9px;
            background: #2459d3;
            color: white;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            main {
                padding: 24px 14px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('stock-opnames.index') }}">
            ← Kembali ke Daftar Stock Opname
        </a>

        <h1>Detail Stock Opname</h1>
        <div class="muted">
            Informasi pemeriksaan dan penyesuaian stok barang.
        </div>

        <section class="card">
            <div class="detail-grid">

                <div class="item">
                    <span class="label">Tanggal Opname</span>
                    <div class="value">
                        {{ $stockOpname->opname_date->format('d/m/Y') }}
                    </div>
                </div>

                <div class="item">
                    <span class="label">Nama Barang</span>
                    <div class="value">
                        {{ $stockOpname->product->name }}
                    </div>
                </div>

                <div class="item">
                    <span class="label">Kode Barang</span>
                    <div class="value">
                        {{ $stockOpname->product->code ?? '-' }}
                    </div>
                </div>

                <div class="item">
                    <span class="label">Petugas</span>
                    <div class="value">
                        {{ $stockOpname->user->name ?? '-' }}
                    </div>
                </div>

                <div class="item">
                    <span class="label">Stok Sistem</span>
                    <div class="value">
                        {{ $stockOpname->system_stock }}
                        {{ $stockOpname->product->unit }}
                    </div>
                </div>

                <div class="item">
                    <span class="label">Stok Fisik</span>
                    <div class="value">
                        {{ $stockOpname->physical_stock }}
                        {{ $stockOpname->product->unit }}
                    </div>
                </div>

                <div class="item">
                    <span class="label">Selisih Stok</span>
                    <div class="value">
                        @if ($stockOpname->difference > 0)
                        <span class="difference positive">
                            +{{ $stockOpname->difference }}
                        </span>
                        @elseif ($stockOpname->difference < 0)
                            <span class="difference negative">
                            {{ $stockOpname->difference }}
                            </span>
                            @else
                            <span class="difference neutral">0</span>
                            @endif
                    </div>
                </div>

                <div class="item">
                    <span class="label">Waktu Pencatatan</span>
                    <div class="value">
                        {{ $stockOpname->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

            </div>

            <div class="notes">
                <strong>Catatan:</strong>
                <p>
                    {{ $stockOpname->notes ?: 'Tidak ada catatan.' }}
                </p>
            </div>

            <a class="button" href="{{ route('stock-opnames.index') }}">
                Kembali
            </a>
        </section>
    </main>
</body>

</html>