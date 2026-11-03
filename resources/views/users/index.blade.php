<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Stockify</title>
    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 24px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow-x: auto;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border: 0;
            cursor: pointer;
            font-size: 14px;
        }

        .secondary {
            background: #64748b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            white-space: nowrap;
        }

        th {
            color: #64748b;
            font-size: 13px;
        }

        .message {
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
        }

        .role {
            padding: 5px 9px;
            border-radius: 6px;
            background: #dbeafe;
            color: #1d4ed8;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .card {
                padding: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <p><a href="{{ route('settings.index') }}">← Kembali ke Pengaturan</a></p>

        <h1>Kelola Pengguna</h1>
        <p>Kelola akun Admin dan Staff Stockify.</p>

        @if (session('success'))
        <div class="message">{{ session('success') }}</div>
        @endif

        <p>
            <a href="{{ route('users.create') }}" class="button">
                + Tambah Pengguna
            </a>
        </p>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Dibuat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <span class="role">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>

                        <td>{{ $user->created_at->format('d/m/Y') }}</td>

                        <td>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </td>

                        <td>
                            <a href="{{ route('users.edit', $user) }}">Edit</a>

                            <form method="POST"
                                action="{{ route('users.toggle-active', $user) }}"
                                style="display: inline"
                                onsubmit="return confirm('Yakin ingin mengubah status akun ini?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">Belum ada pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

            <div style="margin-top: 20px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</body>

</html>