<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;
            background: #f5f7fb;
            color: #1e293b;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 850px;
            margin: auto;
        }

        .back {
            color: #3159d5;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 22px;
        }

        h1 {
            margin-bottom: 8px;
            font-size: 30px;
        }

        .subtitle {
            color: #718096;
            margin-bottom: 28px;
        }

        .card {
            background: white;
            padding: 30px;
            border: 1px solid #e5eaf2;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, .03);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dbe2ee;
            border-radius: 9px;
            font-size: 14px;
            outline: none;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            background: white;
            font-size: 14px;
            color: #172033;
            font-family: Arial, sans-serif;
        }

        input:focus,
        select:focus {
            outline: 2px solid #d7e2ff;
            border-color: #2459d3;
        }

        input:focus {
            border-color: #3159d5;
            box-shadow: 0 0 0 3px #3159d51a;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
        }

        .alert {
            background: #fef2f2;
            color: #b91c1c;
            padding: 14px;
            border-radius: 9px;
            margin-bottom: 22px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        .button {
            border: 0;
            border-radius: 9px;
            padding: 12px 18px;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .cancel {
            background: #f1f5f9;
            color: #334155;
        }

        .save {
            background: #3159d5;
            color: white;
        }

        .save:hover {
            background: #2448bd;
        }

        @media (max-width: 600px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <a href="{{ route('products.index') }}" class="back">
            &larr; Kembali ke Data Barang
        </a>

        <h1>Edit Barang</h1>
        <p class="subtitle">
            Perbarui informasi barang yang tersimpan di Stockify.
        </p>

        <section class="card">
            @if ($errors->any())
            <div class="alert">
                <strong>Periksa kembali data yang kamu masukkan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('products.update', $product) }}"
                method="POST">
                @csrf
                @method('PUT')

                <div class="grid">
                    <div class="field">
                        <label for="code">Kode Barang *</label>
                        <input id="code" name="code" type="text"
                            value="{{ old('code', $product->code) }}"
                            required>
                    </div>

                    <div class="field">
                        <label for="name">Nama Barang *</label>
                        <input id="name" name="name" type="text"
                            value="{{ old('name', $product->name) }}"
                            required>
                    </div>


                    <div class="field">
                        <label for="category_id">Kategori</label>

                        <select id="category_id" name="category_id">
                            <option value="">-- Pilih Kategori --</option>

                            @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected((string) old('category_id', $product->category_id) === (string) $category->id)
                                >
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>

                        @error('category_id')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="field">
                        <label for="stock">Jumlah Stok *</label>
                        <input id="stock" name="stock" type="number"
                            min="0" value="{{ old('stock', $product->stock) }}"
                            required>
                    </div>

                    <div class="field">
                        <label for="unit">Satuan *</label>
                        <input id="unit" name="unit" type="text"
                            value="{{ old('unit', $product->unit) }}"
                            required>
                    </div>

                    <div class="field">
                        <label for="location">Lokasi Penyimpanan</label>
                        <input
                            id="location"
                            name="location"
                            type="text"
                            value="{{ old('location', $product->location) }}"
                            placeholder="Contoh: Gudang A - Rak A1"
                            maxlength="255">

                        @error('location')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="minimum_stock">Batas Minimum Stok *</label>
                        <input id="minimum_stock" name="minimum_stock"
                            type="number" min="0"
                            value="{{ old('minimum_stock', $product->minimum_stock) }}"
                            required>
                    </div>

                    <div class="field">
                        <label for="purchase_price">Harga Beli (Rp) *</label>
                        <input id="purchase_price" name="purchase_price"
                            type="number" min="0" step="0.01"
                            value="{{ old('purchase_price', $product->purchase_price) }}"
                            required>
                    </div>

                    <div class="field">
                        <label for="selling_price">Harga Jual (Rp) *</label>
                        <input id="selling_price" name="selling_price"
                            type="number" min="0" step="0.01"
                            value="{{ old('selling_price', $product->selling_price) }}"
                            required>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('products.index') }}"
                        class="button cancel">Batal</a>

                    <button type="submit" class="button save">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>