<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backup Database - Stockify</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 28px;
            background: #f1f5f9;
            color: #1e293b;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        a {
            color: #2563eb;
        }

        h1 {
            margin-bottom: 8px;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 24px;
            margin-top: 22px;
        }

        .muted {
            color: #64748b;
        }

        label {
            display: block;
            font-weight: bold;
            margin: 14px 0 8px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }

        button {
            border: 0;
            border-radius: 8px;
            padding: 12px 16px;
            background: #315be8;
            color: white;
            cursor: pointer;
            margin-top: 14px;
        }

        .danger {
            background: #b91c1c;
        }

        .success {
            color: #166534;
        }

        .error {
            color: #b91c1c;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 520px;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        th {
            background: #f8fafc;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }

            .card {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('settings.index') }}">← Kembali ke Pengaturan</a>

        <h1>Backup dan Restore Database</h1>
        <p class="muted">Kelola cadangan data inventaris Stockify.</p>

        @if (session('success'))
        <div class="card success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
        <div class="card error">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <section class="card">
            <h2>Buat Backup</h2>
            <p class="muted">
                Membuat file SQL berisi salinan database saat ini.
            </p>

            <form method="POST" action="{{ route('settings.backup.store') }}">
                @csrf
                <button type="submit">Buat Backup Sekarang</button>
            </form>
        </section>

        <section class="card">
            <h2>Daftar Backup</h2>

            @if ($files->isEmpty())
            <p class="muted">Belum ada file backup.</p>
            @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama File</th>
                            <th>Ukuran</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $file)
                        <tr>
                            <td>{{ $file['name'] }}</td>
                            <td>{{ number_format($file['size'] / 1024, 1) }} KB</td>
                            <td>{{ $file['date'] }}</td>
                            <td>
                                <a href="{{ route('settings.backup.download', ['filename' => $file['name']]) }}">
                                    Unduh
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </section>

        <section class="card">
            <h2>Restore Database</h2>
            <p class="error">
                Peringatan: restore dapat menimpa data yang sedang digunakan.
                Sistem akan membuat backup pengaman terlebih dahulu.
            </p>

            <form method="POST"
                action="{{ route('settings.backup.restore') }}"
                enctype="multipart/form-data"
                onsubmit="return confirm('Yakin ingin memulihkan database? Pastikan file SQL benar.');">
                @csrf

                <label for="backup_file">File backup SQL</label>
                <input id="backup_file" type="file" name="backup_file"
                    accept=".sql" required>

                <label for="confirmation">
                    Ketik RESTORE untuk mengonfirmasi
                </label>
                <input id="confirmation" name="confirmation"
                    autocomplete="off" required>

                <button class="danger" type="submit">Restore Database</button>
            </form>
        </section>
    </div>
</body>

</html>