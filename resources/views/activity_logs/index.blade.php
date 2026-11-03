<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log Aktivitas - Stockify</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .back {
            color: #2563eb;
            text-decoration: none;
        }

        h1 {
            margin-bottom: 8px;
        }

        .muted {
            color: #64748b;
        }

        .card {
            margin-top: 24px;
            padding: 24px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
        }

        .field {
            flex: 1;
            min-width: 150px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }

        button {
            padding: 11px 16px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th,
        td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            color: #475569;
        }

        .pagination {
            margin-top: 20px;
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
        <a class="back" href="{{ route('dashboard') }}">← Kembali ke Dashboard</a>

        <h1>Log Aktivitas</h1>
        <p class="muted">Riwayat aktivitas pengguna di Stockify.</p>

        <div class="card">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="filters">
                <div class="field">
                    <label for="search">Cari pengguna atau aktivitas</label>
                    <input id="search" name="search" value="{{ request('search') }}"
                        placeholder="Nama, email, deskripsi...">
                </div>

                <div class="field">
                    <label for="action">Jenis aktivitas</label>
                    <select id="action" name="action">
                        <option value="">Semua aktivitas</option>
                        @foreach ([
                        'product_created' => 'Tambah barang',
                        'product_updated' => 'Edit barang',
                        'product_deleted' => 'Hapus barang',
                        'stock_in' => 'Stok masuk',
                        'stock_out' => 'Stok keluar',
                        ] as $value => $label)
                        <option value="{{ $value }}" @selected(request('action')===$value)>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="start_date">Dari tanggal</label>
                    <input type="date" id="start_date" name="start_date"
                        value="{{ request('start_date') }}">
                </div>

                <div class="field">
                    <label for="end_date">Sampai tanggal</label>
                    <input type="date" id="end_date" name="end_date"
                        value="{{ request('end_date') }}">
                </div>

                <button type="submit">Filter</button>
                <a href="{{ route('activity-logs.index') }}">Reset</a>
            </form>
        </div>

        <div class="card">
            <h2>Daftar Aktivitas</h2>
            <p class="muted">Total hasil: {{ $logs->total() }}</p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Aktivitas</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td>
                                {{ $log->user?->name ?? 'Pengguna dihapus' }}
                                @if ($log->user)
                                <br><small class="muted">{{ $log->user->email }}</small>
                                @endif
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->action }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</body>

</html>