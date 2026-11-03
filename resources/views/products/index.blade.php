<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Barang — Stockify</title>
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

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            background: #e8f7ee;
            color: #217849;
        }

        .low {
            background: #fff0e6;
            color: #b45309;
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
            background: #e8f7ee;
            color: #217849;
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
        <a class="back" href="{{ route('dashboard') }}">← Kembali ke Dashboard</a>

        <div class="heading">
            <div>
                <h1>Data Barang</h1>
                <div class="muted">Kelola informasi dan persediaan produk kamu.</div>
            </div>
            @if(auth()->user()->role === 'admin')
            <a class="button" href="{{ route('products.create') }}">+ Tambah Barang</a>
            @endif
        </div>

        @if (session('success'))
        <div class="alert">{{ session('success') }}</div>
        @endif

        <section class="card">
            <form class="toolbar" method="GET" action="{{ route('products.index') }}">
                <strong>Daftar Produk ({{ $products->total() }})</strong>
                <div>
                    <input type="search" name="search" value="{{ $search }}"
                        placeholder="Cari kode, nama, kategori...">

                    <button class="button" type="submit">Cari</button>

                    <button class="button" type="button" onclick="openScanner()">
                        Scan Barcode / QR
                    </button>

                    @if ($search)
                    <a href="{{ route('products.index') }}" class="muted">Reset</a>
                    @endif
                </div>
            </form>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th style="min-width: 190px;">Barcode</th>
                            <th style="min-width: 120px;">QR Code</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi Penyimpanan</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Status Stok</th>
                            <th>Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->code }}</td>
                            <td style="min-width: 190px; padding: 12px;">
                                <svg class="product-barcode" data-code="{{ $product->code }}"></svg>
                            </td>

                            <td style="min-width: 120px; padding: 12px;">
                                <div class="product-qrcode" data-code="{{ $product->code }}"></div>
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category()->first()?->name ?? $product->category ?? '-' }}</td>
                            <td>{{ $product->location ?: '-' }}</td>
                            <td>{{ $product->stock }} {{ $product->unit }}</td>
                            <td>Rp {{ number_format((float) $product->purchase_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format((float) $product->selling_price, 0, ',', '.') }}</td>
                            <td>
                                @if ($product->stock <= $product->minimum_stock)
                                    <span class="badge low">Stok menipis</span>
                                    @else
                                    <span class="badge">Tersedia</span>
                                    @endif
                            </td>
                            <td>
                                <div class="actions">
                                    @if(auth()->user()->role === 'admin')
                                    <a class="edit" href="{{ route('products.edit', $product) }}">Edit</a>
                                    <button
                                        type="button"
                                        class="delete"
                                        style="border:0;background:none;cursor:pointer"
                                        data-name="{{ $product->name }}"
                                        data-url="{{ route('products.destroy', $product) }}"
                                        onclick="openDeleteModal(this)">
                                        Hapus
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11">
                                <div class="empty">
                                    <h3>Belum ada barang</h3>
                                    <p>Tambahkan barang pertama untuk mulai mengelola stok.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">{{ $products->links() }}</div>
        </section>
    </main>


    <div id="confirmDeleteModal"
        style="display:none;position:fixed;inset:0;z-index:9999;
    background:rgba(15,23,42,.55);align-items:center;
    justify-content:center;padding:20px;">

        <div style="background:white;width:100%;max-width:440px;
        padding:28px;border-radius:16px;
        box-shadow:0 20px 60px rgba(0,0,0,.2);">

            <div style="font-size:36px;margin-bottom:12px;">⚠️</div>

            <h2 style="margin:0 0 12px;color:#172033;">
                Konfirmasi Hapus Barang
            </h2>

            <p style="color:#667085;line-height:1.7;">
                Kamu yakin ingin menghapus barang
                <strong id="deleteProductName"></strong>?
            </p>

            <p style="background:#fff0e6;color:#92400e;padding:12px;
            border-radius:8px;font-size:13px;line-height:1.6;">
                Jika barang memiliki riwayat transaksi stok, riwayat tersebut
                juga dapat terhapus. Data yang terhapus tidak dapat dipulihkan
                melalui halaman ini.
            </p>

            <div style="display:flex;justify-content:flex-end;
            gap:10px;margin-top:22px;">

                <button type="button" onclick="closeDeleteModal()"
                    style="padding:11px 16px;border:0;border-radius:8px;
                background:#f0f2f7;color:#475467;cursor:pointer;">
                    Batal
                </button>

                <form id="deleteProductForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        style="padding:11px 16px;border:0;border-radius:8px;
                    background:#c0392b;color:white;cursor:pointer;">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(button) {
            const name = button.dataset.name;
            const url = button.dataset.url;

            document.getElementById('deleteProductName').textContent = name;
            document.getElementById('deleteProductForm').action = url;
            document.getElementById('confirmDeleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('confirmDeleteModal').style.display = 'none';
        }

        document.getElementById('confirmDeleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        document.querySelectorAll('.product-barcode').forEach(function(element) {
            const code = element.dataset.code;

            if (!code) return;

            JsBarcode(element, code, {
                format: 'CODE128',
                width: 1.7,
                height: 45,
                displayValue: true,
                fontSize: 12,
                margin: 5
            });
        });

        document.querySelectorAll('.product-qrcode').forEach(function(element) {
            const code = element.dataset.code;

            if (!code) return;

            new QRCode(element, {
                text: code,
                width: 90,
                height: 90,
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    </script>


    <div id="scannerModal"
        style="display:none;position:fixed;inset:0;z-index:10000;
    background:rgba(15,23,42,.65);align-items:center;
    justify-content:center;padding:20px;">

        <div style="background:white;width:100%;max-width:500px;
        padding:24px;border-radius:14px;">

            <h2 style="margin-top:0;">Scan Barcode / QR Code</h2>
            <p>Arahkan kamera ke barcode atau QR Code barang.</p>

            <div id="reader" style="width:100%;"></div>

            <p id="scannerMessage" style="font-size:13px;color:#667085;">
                Menyiapkan kamera...
            </p>

            <button class="button" type="button" onclick="closeScanner()">
                Tutup Kamera
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let html5QrCode = null;
        let scannerRunning = false;

        window.openScanner = async function() {
            const modal = document.getElementById('scannerModal');
            const message = document.getElementById('scannerMessage');

            modal.style.display = 'flex';
            message.textContent = 'Mengaktifkan kamera...';

            try {
                // Hentikan scanner sebelumnya jika masih ada.
                if (html5QrCode && scannerRunning) {
                    await html5QrCode.stop();
                    await html5QrCode.clear();
                }

                html5QrCode = new Html5Qrcode('reader');

                await html5QrCode.start({
                        facingMode: 'environment'
                    }, {
                        fps: 15,
                        qrbox: function(viewfinderWidth, viewfinderHeight) {
                            return {
                                width: Math.min(viewfinderWidth * 0.9, 450),
                                height: Math.min(viewfinderHeight * 0.65, 220)
                            };
                        },
                        disableFlip: false,
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.EAN_13,
                            Html5QrcodeSupportedFormats.EAN_8,
                            Html5QrcodeSupportedFormats.UPC_A,
                            Html5QrcodeSupportedFormats.UPC_E,
                            Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.CODE_39,
                            Html5QrcodeSupportedFormats.QR_CODE
                        ]
                    },
                    async function(decodedText) {
                            if (!scannerRunning) return;

                            scannerRunning = false;
                            message.textContent = 'Kode terbaca: ' + decodedText;

                            document.querySelector('input[name="search"]').value =
                                decodedText;

                            await window.closeScanner();

                            document.querySelector('.toolbar').requestSubmit();
                        },
                        function() {
                            // Abaikan frame yang belum menghasilkan kode.
                        }
                );

                scannerRunning = true;
                message.textContent = 'Kamera aktif. Arahkan ke barcode.';
            } catch (error) {
                scannerRunning = false;
                message.textContent =
                    'Kamera gagal dibuka. Tutup aplikasi lain yang memakai kamera, lalu coba lagi.';

                console.error('Scanner error:', error);
            }
        };

        window.closeScanner = async function() {
            scannerRunning = false;

            if (html5QrCode) {
                try {
                    if (html5QrCode.isScanning) {
                        await html5QrCode.stop();
                    }
                    await html5QrCode.clear();
                } catch (error) {
                    console.warn('Gagal menghentikan scanner:', error);
                }

                html5QrCode = null;
            }

            document.getElementById('scannerModal').style.display = 'none';
        };
    </script>

</body>

</html>