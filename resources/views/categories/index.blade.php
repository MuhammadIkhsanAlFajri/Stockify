<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Barang - Stockify</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }

        .container {
            max-width: 1000px;
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
            padding: 10px 14px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .secondary {
            background: #e5e7eb;
            color: #1f2937;
        }

        .danger {
            background: #dc2626;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 22px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            color: #6b7280;
            font-size: 13px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .actions form {
            margin: 0;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div>
                <h1>Kategori Barang</h1>
                <p>Kelola kelompok kategori produk Stockify.</p>
            </div>
            <div>
                <a class="button secondary" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="button" href="{{ route('categories.create') }}">+ Tambah Kategori</a>
            </div>
        </header>

        @if (session('success'))
        <div class="success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
        <div style="margin-bottom: 20px; padding: 14px; border-radius: 8px; background: #fff0f0; color: #c62828;">
            {{ session('error') }}
        </div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Produk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->description ?: '-' }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <div class="actions">
                                <a class="button" href="{{ route('categories.edit', $category) }}">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="button danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:30px">
                            Belum ada kategori. Klik Tambah Kategori untuk memulai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination">{{ $categories->links() }}</div>
        </div>
    </div>
</body>

</html>