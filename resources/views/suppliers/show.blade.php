<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Supplier — Stockify</title>

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

        .card {
            margin-top: 25px;
            padding: 28px;
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .muted {
            color: #7d8798;
            font-size: 14px;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
            margin-top: 28px;
        }

        .label {
            margin-bottom: 7px;
            color: #7d8798;
            font-size: 13px;
        }

        .value {
            overflow-wrap: anywhere;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 9px;
            background: #2459d3;
            color: white;
            font-size: 14px;
        }

        .secondary {
            background: #edf1f7;
            color: #344054;
        }

        @media (max-width: 600px) {
            main {
                padding: 24px 12px;
            }

            .card {
                padding: 20px;
            }

            .details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('suppliers.index') }}">
            ← Kembali ke Daftar Supplier
        </a>

        <section class="card">
            <h1>{{ $supplier->name }}</h1>
            <p class="muted">Detail informasi pemasok Stockify.</p>

            <div class="details">
                <div>
                    <div class="label">Nama Supplier</div>
                    <div class="value">{{ $supplier->name }}</div>
                </div>

                <div>
                    <div class="label">Nama Perusahaan</div>
                    <div class="value">{{ $supplier->company ?: '-' }}</div>
                </div>

                <div>
                    <div class="label">Email</div>
                    <div class="value">{{ $supplier->email ?: '-' }}</div>
                </div>

                <div>
                    <div class="label">Nomor Telepon</div>
                    <div class="value">{{ $supplier->phone ?: '-' }}</div>
                </div>

                <div>
                    <div class="label">Alamat</div>
                    <div class="value">{{ $supplier->address ?: '-' }}</div>
                </div>

                <div>
                    <div class="label">Catatan</div>
                    <div class="value">{{ $supplier->notes ?: '-' }}</div>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            <div class="actions">
                <a class="button" href="{{ route('suppliers.edit', $supplier) }}">
                    Edit Supplier
                </a>

                <a class="button secondary" href="{{ route('suppliers.index') }}">
                    Kembali
                </a>
            </div>
            @endif
        </section>
    </main>
</body>

</html>