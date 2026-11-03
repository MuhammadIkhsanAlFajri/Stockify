<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

class StockMovementController extends Controller
{

    public function index(Request $request)
    {
        $query = StockMovement::with('product')->latest();

        // Cari berdasarkan kode atau nama produk
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('product', function ($productQuery) use ($search) {
                $productQuery->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter jenis transaksi
        if (in_array($request->type, ['in', 'out'], true)) {
            $query->where('type', $request->type);
        }

        // Filter tanggal mulai
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Filter tanggal akhir
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->paginate(10)->withQueryString();

        return view('stock_movements.index', compact('movements'));
    }



    public function create(Request $request)
    {
        $type = $request->query('type', 'in');

        if (! in_array($type, ['in', 'out'], true)) {
            $type = 'in';
        }

        $products = Product::orderBy('name')->get();

        return view(
            'stock_movements.create',
            compact('products', 'type')
        );
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($validated['type'] === 'out') {
                if ($product->stock < $validated['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Stok tidak mencukupi. Stok tersedia: '
                            . $product->stock,
                    ]);
                }

                $product->decrement('stock', $validated['quantity']);
            } else {
                $product->increment('stock', $validated['quantity']);
            }

            StockMovement::create($validated);

            ActivityLog::create([
                'user_id' => $request->user()->id,
                'action' => $validated['type'] === 'in'
                    ? 'stock_in'
                    : 'stock_out',
                'description' => sprintf(
                    'Stok %s sebanyak %d untuk barang: %s',
                    $validated['type'] === 'in' ? 'masuk' : 'keluar',
                    $validated['quantity'],
                    $product->name
                ),
                'subject_type' => Product::class,
                'subject_id' => $product->id,
                'properties' => [
                    'type' => $validated['type'],
                    'quantity' => $validated['quantity'],
                    'stock_after' => $product->fresh()->stock,
                    'note' => $validated['note'] ?? null,
                ],
            ]);
        });

        return redirect()
            ->route('stock-movements.index')
            ->with('success', 'Pergerakan stok berhasil dicatat.');
    }
}
