<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Stock Opname - Stockify</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f7f9fc;
            color: #172033;
        }

        .container {
            max-width: 750px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 26px;
        }

        .subtitle {
            color: #64748b;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #dbe1ea;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #2459d3;
        }

        input[readonly] {
            background: #f1f5f9;
            color: #475569;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .difference-box {
            padding: 16px;
            background: #edf2ff;
            border-radius: 9px;
            margin-bottom: 22px;
        }

        .difference-title {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 7px;
        }

        .difference-value {
            font-size: 25px;
            font-weight: bold;
            color: #2459d3;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #2459d3;
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        .info {
            color: #64748b;
            font-size: 12px;
            margin-top: 6px;
        }

        @media(max-width: 600px) {
            body {
                padding: 15px;
            }

            .row {
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
    <div class="container">

        <div class="header">
            <h1>Tambah Stock Opname</h1>
            <p class="subtitle">
                Catat hasil pemeriksaan stok fisik barang.
            </p>
        </div>

        <div class="card">

            @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:14px;border-radius:8px;margin-bottom:20px;">
                <strong>Periksa kembali data berikut:</strong>
                <ul style="margin-bottom:0;">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('stock-opnames.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="product_id">Pilih Produk</label>
                    <select name="product_id" id="product_id" required>
                        <option value="">-- Pilih Produk --</option>

                        @foreach ($products as $product)
                        <option
                            value="{{ $product->id }}"
                            data-stock="{{ $product->stock }}"
                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->code }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="opname_date">Tanggal Pemeriksaan</label>
                    <input
                        type="date"
                        name="opname_date"
                        id="opname_date"
                        value="{{ old('opname_date', date('Y-m-d')) }}"
                        required>
                    @error('opname_date')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="form-group">
                        <label for="system_stock">Stok Sistem</label>
                        <input
                            type="number"
                            id="system_stock"
                            readonly
                            placeholder="Pilih produk dahulu">
                        <div class="info">Diambil otomatis dari database.</div>
                    </div>

                    <div class="form-group">
                        <label for="physical_stock">Stok Fisik</label>
                        <input
                            type="number"
                            name="physical_stock"
                            id="physical_stock"
                            min="0"
                            value="{{ old('physical_stock') }}"
                            placeholder="Masukkan jumlah fisik"
                            required>
                        @error('physical_stock')
                        <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="difference-box">
                    <div class="difference-title">Selisih Stok</div>
                    <div class="difference-value" id="difference">-</div>
                    <div class="info" id="difference_info">
                        Pilih produk dan masukkan stok fisik.
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Catatan (Opsional)</label>
                    <textarea
                        name="notes"
                        id="notes"
                        placeholder="Contoh: Pemeriksaan stok gudang utama">{{ old('notes') }}</textarea>
                </div>

                <div class="actions">
                    <a href="{{ route('stock-opnames.index') }}"
                        class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Stock Opname
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const productSelect = document.getElementById('product_id');
        const systemStockInput = document.getElementById('system_stock');
        const physicalStockInput = document.getElementById('physical_stock');
        const differenceDisplay = document.getElementById('difference');
        const differenceInfo = document.getElementById('difference_info');

        function calculateDifference() {
            const option = productSelect.options[productSelect.selectedIndex];

            if (!option || !option.value) {
                systemStockInput.value = '';
                differenceDisplay.textContent = '-';
                differenceInfo.textContent = 'Pilih produk dan masukkan stok fisik.';
                return;
            }

            const systemStock = Number(option.dataset.stock);
            systemStockInput.value = systemStock;

            if (physicalStockInput.value === '') {
                differenceDisplay.textContent = '-';
                differenceInfo.textContent = 'Masukkan jumlah stok fisik.';
                return;
            }

            const physicalStock = Number(physicalStockInput.value);
            const difference = physicalStock - systemStock;

            differenceDisplay.textContent =
                difference > 0 ? '+' + difference : difference;

            if (difference > 0) {
                differenceInfo.textContent = 'Stok fisik lebih banyak dari stok sistem.';
            } else if (difference < 0) {
                differenceInfo.textContent = 'Stok fisik lebih sedikit dari stok sistem.';
            } else {
                differenceInfo.textContent = 'Stok fisik sesuai dengan stok sistem.';
            }
        }

        productSelect.addEventListener('change', calculateDifference);
        physicalStockInput.addEventListener('input', calculateDifference);

        calculateDifference();
    </script>

</body>

</html>