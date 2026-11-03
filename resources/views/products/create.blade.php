<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Barang — Stockify</title>
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
            padding: 35px 22px;
        }

        a {
            text-decoration: none;
        }

        .back {
            color: #2459d3;
            font-size: 14px;
        }

        h1 {
            margin: 22px 0 8px;
            font-size: 29px;
        }

        .muted {
            color: #7d8798;
            font-size: 14px;
            line-height: 1.6;
        }

        .card {
            margin-top: 25px;
            padding: 28px;
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            background: white;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: 2px solid #d7e2ff;
            border-color: #2459d3;
        }

        .required {
            color: #d13b3b;
        }

        .error {
            color: #d13b3b;
            font-size: 12px;
            margin-top: 6px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #edf0f5;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border: 0;
            border-radius: 8px;
            background: #2459d3;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .cancel {
            background: #f0f2f7;
            color: #475467;
        }

        .alert {
            margin-top: 20px;
            padding: 14px;
            border-radius: 8px;
            background: #fff0e6;
            color: #b45309;
        }

        @media (max-width: 600px) {
            .grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('products.index') }}">← Kembali ke Data Barang</a>

        <h1>Tambah Barang</h1>
        <p class="muted">Masukkan informasi barang yang akan dicatat dalam inventaris.</p>

        @if ($errors->any())
        <div class="alert">Ada data yang perlu diperbaiki. Periksa pesan di bawah setiap kolom.</div>
        @endif

        <form class="card" method="POST" action="{{ route('products.store') }}">
            @csrf

            <div class="grid">
                <div class="field">
                    <label for="code">Kode Barang <span class="required">*</span></label>
                    <input id="code" name="code" value="{{ old('code') }}"
                        placeholder="Contoh: BRG-001" required maxlength="255">
                    @error('code') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="name">Nama Barang <span class="required">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}"
                        placeholder="Contoh: Keyboard Wireless" required maxlength="255">
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>



                <div class="field">
                    <label for="category_id">Kategori</label>

                    <select name="category_id" id="category_id">
                        <option value="">-- Pilih Kategori --</option>

                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id')==$category->id)>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                    @error('category_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="field">
                    <label for="unit">Satuan <span class="required">*</span></label>
                    <select id="unit" name="unit" required>
                        @foreach (['pcs', 'unit', 'box', 'pack', 'kg', 'liter', 'meter', 'lusin'] as $unit)
                        <option value="{{ $unit }}" @selected(old('unit', 'pcs' )===$unit)>
                            {{ strtoupper($unit) }}
                        </option>
                        @endforeach
                    </select>
                    @error('unit') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="location">Lokasi Penyimpanan</label>
                    <input
                        id="location"
                        name="location"
                        value="{{ old('location') }}"
                        placeholder="Contoh: Gudang A - Rak A1"
                        maxlength="255">

                    @error('location')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="stock">Stok Awal <span class="required">*</span></label>
                    <input id="stock" type="number" name="stock" min="0"
                        value="{{ old('stock', 0) }}" required>
                    @error('stock') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="minimum_stock">Batas Minimum Stok <span class="required">*</span></label>
                    <input id="minimum_stock" type="number" name="minimum_stock" min="0"
                        value="{{ old('minimum_stock', 5) }}" required>
                    @error('minimum_stock') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="purchase_price">Harga Beli (Rp) <span class="required">*</span></label>
                    <input id="purchase_price" type="number" name="purchase_price" min="0"
                        step="0.01" value="{{ old('purchase_price', 0) }}" required>
                    @error('purchase_price') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label for="selling_price">Harga Jual (Rp) <span class="required">*</span></label>
                    <input id="selling_price" type="number" name="selling_price" min="0"
                        step="0.01" value="{{ old('selling_price', 0) }}" required>
                    @error('selling_price') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="actions">
                <a class="button cancel" href="{{ route('products.index') }}">Batal</a>
                <button class="button" type="submit">Simpan Barang</button>
            </div>
        </form>
    </main>
</body>

</html>