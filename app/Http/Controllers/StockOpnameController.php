<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockMovement;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{

    public function index(Request $request)
    {
        $query = StockOpname::with(['product', 'user']);

        // Pencarian nama atau kode barang
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter tanggal awal
        if ($request->filled('date_from')) {
            $query->whereDate('opname_date', '>=', $request->date_from);
        }

        // Filter tanggal akhir
        if ($request->filled('date_to')) {
            $query->whereDate('opname_date', '<=', $request->date_to);
        }

        $opnames = $query
            ->latest('opname_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('stock-opnames.index', compact('opnames'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('stock-opnames.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'opname_date' => ['required', 'date'],
            'physical_stock' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $systemStock = $product->stock;
            $physicalStock = $validated['physical_stock'];
            $difference = $physicalStock - $systemStock;

            StockOpname::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'opname_date' => $validated['opname_date'],
                'system_stock' => $systemStock,
                'physical_stock' => $physicalStock,
                'difference' => $difference,
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($difference !== 0) {
                $product->update([
                    'stock' => $physicalStock,
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => $difference > 0 ? 'in' : 'out',
                    'quantity' => abs($difference),
                    'note' => 'Penyesuaian stok melalui stock opname',
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Stock opname',
                'description' => 'Stock opname produk: ' . $product->name
                    . '. Stok sistem: ' . $systemStock
                    . ', stok fisik: ' . $physicalStock
                    . ', selisih: ' . $difference,
                'subject_type' => Product::class,
                'subject_id' => $product->id,
                'properties' => [
                    'system_stock' => $systemStock,
                    'physical_stock' => $physicalStock,
                    'difference' => $difference,
                ],
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('stock-opnames.index')
            ->with('success', 'Stock opname berhasil disimpan dan stok telah diperbarui.');
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['product', 'user']);

        return view('stock-opnames.show', compact('stockOpname'));
    }

    public function report(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = StockOpname::with(['product', 'user']);

        if (!empty($filters['start_date'])) {
            $query->whereDate('opname_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('opname_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $opnames = (clone $query)
            ->latest('opname_date')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totalOpnames = (clone $query)->count();
        $totalDifference = (clone $query)->sum('difference');
        $totalIncrease = (clone $query)->where('difference', '>', 0)->sum('difference');
        $totalDecrease = abs((clone $query)->where('difference', '<', 0)->sum('difference'));

        return view('reports.stock-opname', compact(
            'opnames',
            'filters',
            'totalOpnames',
            'totalDifference',
            'totalIncrease',
            'totalDecrease'
        ));
    }

    public function exportReport(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = StockOpname::with(['product', 'user']);

        if (!empty($filters['start_date'])) {
            $query->whereDate('opname_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('opname_date', '<=', $filters['end_date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $opnames = $query->latest('opname_date')->latest()->get();

        $filename = 'laporan-stock-opname-' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($opnames) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Tanggal',
                'Kode Produk',
                'Nama Produk',
                'Stok Sistem',
                'Stok Fisik',
                'Selisih',
                'Petugas',
                'Catatan',
            ], ',');

            foreach ($opnames as $opname) {
                fputcsv($output, [
                    $opname->opname_date?->format('d/m/Y') ?? '-',
                    $opname->product?->code ?? '-',
                    $opname->product?->name ?? 'Produk telah dihapus',
                    $opname->system_stock,
                    $opname->physical_stock,
                    $opname->difference,
                    $opname->user?->name ?? '-',
                    $opname->notes ?? '-',
                ], ',');
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
