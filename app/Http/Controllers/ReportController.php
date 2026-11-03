<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockOpname;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
    }

    /**
     * Query transaksi stok.
     */
    private function movementQuery(array $filters)
    {
        $query = StockMovement::with('product');

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Query Stock Opname.
     */
    private function opnameQuery(array $filters)
    {
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

        return $query;
    }

    /**
     * Halaman utama laporan.
     */
    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);

        // Data transaksi stok
        $query = $this->movementQuery($filters);

        $movements = (clone $query)
            ->latest()
            ->paginate(20, ['*'], 'movements_page')
            ->withQueryString();

        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');

        $totalStockIn = (clone $query)
            ->where('type', 'in')
            ->sum('quantity');

        $totalStockOut = (clone $query)
            ->where('type', 'out')
            ->sum('quantity');

        // Data Stock Opname
        $opnameQuery = $this->opnameQuery($filters);

        $opnames = (clone $opnameQuery)
            ->latest('opname_date')
            ->latest()
            ->paginate(20, ['*'], 'opnames_page')
            ->withQueryString();

        $totalOpnames = (clone $opnameQuery)->count();

        $totalDifference = (clone $opnameQuery)->sum('difference');

        $totalIncrease = (clone $opnameQuery)
            ->where('difference', '>', 0)
            ->sum('difference');

        $totalDecrease = abs(
            (clone $opnameQuery)
                ->where('difference', '<', 0)
                ->sum('difference')
        );

        return view('reports.index', compact(
            'totalProducts',
            'totalStock',
            'totalStockIn',
            'totalStockOut',
            'movements',
            'opnames',
            'totalOpnames',
            'totalDifference',
            'totalIncrease',
            'totalDecrease',
            'filters'
        ));
    }

    /**
     * Ekspor transaksi stok ke CSV.
     */
    public function export(Request $request)
    {
        $filters = $this->validatedFilters($request);

        $movements = $this->movementQuery($filters)
            ->latest()
            ->get();

        $filename = 'laporan-stockify-' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($movements) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Tanggal',
                'Kode Produk',
                'Nama Produk',
                'Jenis Transaksi',
                'Jumlah',
                'Satuan',
                'Catatan',
            ], ',');

            foreach ($movements as $movement) {
                fputcsv($output, [
                    $movement->created_at?->format('d/m/Y H:i') ?? '-',
                    $movement->product?->code ?? '-',
                    $movement->product?->name ?? 'Produk telah dihapus',
                    $movement->type === 'in' ? 'Stok Masuk' : 'Stok Keluar',
                    $movement->quantity,
                    $movement->product?->unit ?? '-',
                    $movement->note ?? '-',
                ], ',');
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Ekspor Stock Opname ke CSV.
     */
    public function exportOpname(Request $request)
    {
        $filters = $this->validatedFilters($request);

        $opnames = $this->opnameQuery($filters)
            ->latest('opname_date')
            ->latest()
            ->get();

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
