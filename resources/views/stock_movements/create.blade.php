
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Stok — Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #172033;
        }

        main {
            max-width: 850px;
            margin: auto;
            padding: 36px 24px;
        }

        a {
            text-decoration: none;
        }

        .back {
            display: inline-block;
            color: #3158d5;
            margin-bottom: 28px;
        }

        h1 {
            margin: 0 0 10px;
            font-size: 30px;
        }

        .subtitle {
            margin: 0 0 28px;
            color: #788399;
            line-height: 1.6;
        }

        .card {
            background: white;
            border: 1px solid #e5eaf2;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(20, 35, 70, .03);
        }

        .field {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 9px;
            font-size: 14px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #dce3ef;
            border-radius: 9px;
            background: white;
            color: #172033;
            font: inherit;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #3158d5;
            box-shadow: 0 0 0 3px rgba(49, 88, 213, .1);
        }

        textarea {
            resize: vertical;
            min-height: 95px;
        }

        .hint {
            margin-top: 7px;
            font-size: 12px;
            color: #8791a4;
            line-height: 1.5;
        }

        .error {
            margin-top: 7px;
            color: #c0392b;
            font-size: 13px;
        }

        .alert {
            margin-bottom: 22px;
            padding: 14px 16px;
            border-radius: 10px;
            background: #fff0ed;
            color: #a93226;
            line-height: 1.6;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid #edf0f5;
        }

        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 12px 18px;
            border: 0;
            border-radius: 9px;
            background: #3158d5;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .button:hover {
            background: #2448bb;
        }

        .secondary {
            background: #f0f3f8;
            color: #475467;
        }

        .secondary:hover {
            background: #e5eaf2;
        }

        .empty-state {
            text-align: center;
            padding: 24px 10px;
            line-height: 1.7;
        }

        @media (max-width: 600px) {
            main {
                padding: 24px 14px;
            }

            .card {
                padding: 20px 16px;
            }

            .actions {
                flex-direction: column-reverse;
            }

            .actions .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('stock-movements.index') }}">
            ← Kembali ke Riwayat Stok
        </a>

        <h1>Catat Stok</h1>
        <p class="subtitle">
            Catat penambahan atau pengurangan persediaan barang.
            Perubahan stok akan diterapkan setelah transaksi berhasil disimpan.
        </p>

        <section class="card">
            @if ($errors->any())
            <div class="alert">
                <strong>Transaksi belum berhasil disimpan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if ($products->isEmpty())
            <div class="empty-state">
                <h3>Belum ada barang</h3>
                <p>Tambahkan barang terlebih dahulu sebelum mencatat transaksi stok.</p>
                <a class="button" href="{{ route('products.create') }}">
                    + Tambah Barang
                </a>
            </div>
            @else
            <form action="{{ route('stock-movements.store') }}" method="POST">
                @csrf

                <div class="field">
                    <label for="product_id">Pilih Barang *</label>
                    <select id="product_id" name="product_id" required>
                        <option value="">-- Pilih barang --</option>
                        @foreach ($products as $product)
                        <option
                            value="{{ $product->id }}"
                            @selected((string) old('product_id')===(string) $product->id)
                            >
                            {{ $product->code }} — {{ $product->name }}
                            (Stok: {{ $product->stock }} {{ $product->unit }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <div class="hint">Pilih barang yang akan ditambah atau dikurangi stoknya.</div>
                </div>

                <div class="field">
                    <label for="type">Jenis Transaksi *</label>
                    <select id="type" name="type" required>
                        <option value="in" @selected(old('type', $type)==='in' )>
                            ↓ Stok Masuk — menambah persediaan
                        </option>
                        <option value="out" @selected(old('type', $type)==='out' )>
                            ↑ Stok Keluar — mengurangi persediaan
                        </option>
                    </select>
                    @error('type')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label for="quantity">Jumlah Barang *</label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        min="1"
                        step="1"
                        value="{{ old('quantity') }}"
                        placeholder="Masukkan jumlah barang"
                        required>
                    @error('quantity')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <div class="hint">Jumlah harus berupa bilangan bulat minimal 1.</div>
                </div>

                <div class="field">
                    <label for="note">Catatan (Opsional)</label>
                    <textarea
                        id="note"
                        name="note"
                        rows="3"
                        maxlength="255"
                        placeholder="Contoh: Pembelian dari pemasok atau barang terjual">{{ old('note') }}</textarea>
                    @error('note')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="actions">
                    <a class="button secondary" href="{{ route('stock-movements.index') }}">
                        Batal
                    </a>
                    <button class="button" type="submit">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
            @endif
        </section>
    </main>
</body>

</html>