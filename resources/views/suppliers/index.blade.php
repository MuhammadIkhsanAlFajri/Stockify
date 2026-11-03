<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Supplier — Stockify</title>

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

        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .edit {
            color: #2459d3;
        }

        .delete {
            color: #d13b3b;
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
                <h1>Data Supplier</h1>
                <div class="muted">
                    Kelola informasi pemasok barang untuk Stockify.
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            <a class="button" href="{{ route('suppliers.create') }}">
                + Tambah Supplier
            </a>
            @endif
        </div>

        @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
        <div class="alert error">
            {{ $errors->first() }}
        </div>
        @endif

        <section class="card">
            <form class="toolbar" method="GET"
                action="{{ route('suppliers.index') }}">
                <strong>Daftar Supplier ({{ $suppliers->total() }})</strong>

                <div>
                    <input
                        type="search"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, perusahaan, telepon...">

                    <button class="button" type="submit">Cari</button>

                    @if(request('search'))
                    <a class="muted" href="{{ route('suppliers.index') }}">
                        Reset
                    </a>
                    @endif
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Supplier</th>
                            <th>Perusahaan</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($suppliers as $supplier)
                        <tr>
                            <td><strong>{{ $supplier->name }}</strong></td>
                            <td>{{ $supplier->company ?: '-' }}</td>
                            <td>{{ $supplier->email ?: '-' }}</td>
                            <td>{{ $supplier->phone ?: '-' }}</td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($supplier->address ?: '-', 35) }}
                            </td>
                            <td>
                                <div class="actions">
                                    <a class="edit"
                                        href="{{ route('suppliers.show', $supplier) }}">
                                        Detail
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                    <a class="edit"
                                        href="{{ route('suppliers.edit', $supplier) }}">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('suppliers.destroy', $supplier) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="delete"
                                            type="submit"
                                            style="border:0;background:none;cursor:pointer">
                                            Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty">
                                    <h3>Belum ada supplier</h3>
                                    <p>Tambahkan supplier pertama untuk mulai mencatat pemasok.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">{{ $suppliers->links() }}</div>
        </section>
    </main>
</body>

</html>