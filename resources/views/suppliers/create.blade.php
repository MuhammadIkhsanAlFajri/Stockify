<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Supplier — Stockify</title>
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
            max-width: 760px;
            margin: auto;
            padding: 35px 24px;
        }

        .back {
            color: #2459d3;
            text-decoration: none;
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
            margin-top: 0;
            font-size: 27px;
        }

        .muted {
            color: #7d8798;
            font-size: 14px;
        }

        .form-group {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        label span {
            color: #d13b3b;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            font: inherit;
        }

        textarea {
            resize: vertical;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 26px;
        }

        .button,
        .cancel-button {
            display: inline-block;
            padding: 12px 17px;
            border: 0;
            border-radius: 9px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .button {
            background: #2459d3;
            color: white;
        }

        .cancel-button {
            background: #f0f2f7;
            color: #475467;
        }

        .error {
            display: block;
            margin-top: 6px;
            color: #c0392b;
        }

        @media (max-width: 600px) {
            main {
                padding: 24px 12px;
            }

            .card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('suppliers.index') }}">
            ← Kembali ke Supplier
        </a>

        <section class="card">
            <h1>Tambah Supplier</h1>
            <p class="muted">Masukkan informasi pemasok barang.</p>

            @if($errors->any())
            <p style="color:#c0392b">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="{{ route('suppliers.store') }}">
                @include('suppliers._form', [
                'submitLabel' => 'Simpan Supplier'
                ])
            </form>
        </section>
    </main>
</body>

</html>