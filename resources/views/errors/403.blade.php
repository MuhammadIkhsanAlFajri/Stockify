<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .card {
            width: 100%;
            max-width: 480px;
            padding: 35px;
            text-align: center;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
        }

        .code {
            color: #dc2626;
            font-size: 56px;
            font-weight: bold;
        }

        p {
            color: #64748b;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 20px;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
        }

        .button:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="code">403</div>
        <h1>Akses Ditolak</h1>

        <p>
            Maaf, akun kamu tidak memiliki izin untuk membuka halaman ini.
            Silakan kembali ke Dashboard Stockify.
        </p>

        <a href="{{ route('dashboard') }}" class="button">
            ← Kembali ke Dashboard
        </a>
    </div>
</body>

</html>