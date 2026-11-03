<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembelian Baru — Stockify</title>

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
            max-width: 1100px;
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

        .card {
            background: white;
            border: 1px solid #e7ebf2;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #dce2eb;
            border-radius: 8px;
            font: inherit;
            font-size: 14px;
            background: white;
            color: #172033;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .field {
            min-width: 0;
        }

        .table-wrap {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #edf0f5;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #fafbfe;
            color: #667085;
        }

        td input,
        td select {
            min-width: 120px;
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

        .secondary {
            background: #edf2ff;
            color: #2459d3;
        }

        .danger {
            background: #fff0f0;
            color: #d13b3b;
            padding: 9px 12px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .total {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
        }

        .error {
            background: #fff0e6;
            color: #b45309;
        }

        @media (max-width: 650px) {
            main {
                padding: 24px 12px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 17px;
            }

            .actions {
                flex-direction: column;
            }

            .actions .button {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('purchases.index') }}">
            ← Kembali ke Riwayat Pembelian
        </a>

        <div class="heading">
            <h1>Pembelian Baru</h1>
            <div class="muted">
                Catat barang yang dibeli dari pemasok.
                Stok akan bertambah setelah pembelian disimpan.
            </div>
        </div>

        @if($errors->any())
        <div class="alert error">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('purchases.store') }}" id="purchase-form">
            @csrf

            <section class="card">
                <h3>Informasi Pembelian</h3>

                <div class="grid">
                    <div class="field">
                        <label for="supplier_id">Supplier *</label>
                        <select name="supplier_id" id="supplier_id" required>
                            <option value="">Pilih supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                @selected(old('supplier_id')==$supplier->id)>
                                {{ $supplier->name }}
                                {{ $supplier->company ? ' — '.$supplier->company : '' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="invoice_number">Nomor Faktur *</label>
                        <input
                            type="text"
                            name="invoice_number"
                            id="invoice_number"
                            value="{{ old('invoice_number') }}"
                            placeholder="Contoh: INV-2026-001"
                            required>
                    </div>

                    <div class="field">
                        <label for="purchase_date">Tanggal Pembelian *</label>
                        <input
                            type="date"
                            name="purchase_date"
                            id="purchase_date"
                            value="{{ old('purchase_date', date('Y-m-d')) }}"
                            required>
                    </div>

                    <div class="field">
                        <label for="notes">Catatan</label>
                        <textarea
                            name="notes"
                            id="notes"
                            placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </section>

            <section class="card">
                <div class="toolbar">
                    <div>
                        <h3 style="margin:0 0 6px">Daftar Barang</h3>
                        <div class="muted">Pilih produk, jumlah, dan harga beli per unit.</div>
                    </div>

                    <button type="button" class="button secondary" id="add-item">
                        + Tambah Barang
                    </button>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk *</th>
                                <th>Jumlah *</th>
                                <th>Harga Beli/Unit *</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="items-body"></tbody>
                    </table>
                </div>

                <div class="total">
                    Total: <span id="grand-total">Rp 0</span>
                </div>
            </section>

            <div class="actions">
                <a href="{{ route('purchases.index') }}"
                    class="button secondary">Batal</a>
                <button type="submit" class="button">
                    Simpan Pembelian
                </button>
            </div>
        </form>
    </main>

    <script>
        const products = @json($products);
    </script>

    <script>
        const tbody = document.getElementById('items-body');
        const addButton = document.getElementById('add-item');
        let itemIndex = 0;

        function rupiah(value) {
            return 'Rp ' + Number(value || 0).toLocaleString('id-ID', {
                maximumFractionDigits: 2
            });
        }

        function addItem() {
            const index = itemIndex++;
            const row = document.createElement('tr');

            const options = products.map(product => `
            <option value="${product.id}">
                ${String(product.code || '').replace(/&/g, '&amp;').replace(/</g, '&lt;')}
                ${String(product.name).replace(/&/g, '&amp;').replace(/</g, '&lt;')}
                (Stok: ${product.stock})
            </option>
        `).join('');

            row.innerHTML = `
            <td>
                <select name="items[${index}][product_id]" required>
                    <option value="">Pilih produk</option>
                    ${options}
                </select>
            </td>
            <td>
                <input type="number"
                    name="items[${index}][quantity]"
                    min="1" step="1" value="1" required>
            </td>
            <td>
                <input type="number"
                    name="items[${index}][unit_cost]"
                    min="0" step="0.01" value="0" required>
            </td>
            <td class="subtotal">Rp 0</td>
            <td>
                <button type="button" class="button danger remove-item">
                    Hapus
                </button>
            </td>
        `;

            tbody.appendChild(row);

            const productSelect = row.querySelector('select');
            const quantityInput = row.querySelector('[name$="[quantity]"]');
            const costInput = row.querySelector('[name$="[unit_cost]"]');

            productSelect.addEventListener('change', () => {
                const product = products.find(
                    item => String(item.id) === productSelect.value
                );

                if (product) {
                    costInput.value = product.price ?? 0;
                    calculate();
                }
            });

            quantityInput.addEventListener('input', calculate);
            costInput.addEventListener('input', calculate);

            row.querySelector('.remove-item').addEventListener('click', () => {
                row.remove();
                calculate();
            });

            calculate();
        }

        function calculate() {
            let total = 0;

            tbody.querySelectorAll('tr').forEach(row => {
                const quantity = Number(
                    row.querySelector('[name$="[quantity]"]').value
                );
                const cost = Number(
                    row.querySelector('[name$="[unit_cost]"]').value
                );
                const subtotal = quantity * cost;

                row.querySelector('.subtotal').textContent = rupiah(subtotal);
                total += subtotal;
            });

            document.getElementById('grand-total').textContent = rupiah(total);
        }

        addButton.addEventListener('click', addItem);

        document.getElementById('purchase-form').addEventListener('submit', event => {
            if (tbody.querySelectorAll('tr').length === 0) {
                event.preventDefault();
                alert('Tambahkan minimal satu barang sebelum menyimpan pembelian.');
            }
        });

        addItem();
    </script>
</body>

</html>