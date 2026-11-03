<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Stockify</title>
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
            max-width: 650px;
            margin: auto;
        }

        .card {
            background: white;
            padding: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-top: 24px;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font: inherit;
        }

        .button {
            display: inline-block;
            padding: 11px 16px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .secondary {
            background: #e5e7eb;
            color: #1f2937;
        }

        .error {
            color: #b91c1c;
            font-size: 13px;
            margin-top: 6px;
        }

        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="{{ route('categories.index') }}">← Kembali ke Kategori</a>
        <h1>Edit Kategori</h1>
        <p>Perbarui informasi kategori barang.</p>

        <div class="card">
            @if ($errors->any())
            <div class="alert">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <label for="name">Nama Kategori *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    maxlength="255"
                    required>
                @error('name')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="description">Deskripsi (opsional)</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    maxlength="1000">{{ old('description', $category->description) }}</textarea>
                @error('description')
                <div class="error">{{ $message }}</div>
                @enderror

                <div class="actions">
                    <button type="submit" class="button">Simpan Perubahan</button>
                    <a href="{{ route('categories.index') }}"
                        class="button secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>