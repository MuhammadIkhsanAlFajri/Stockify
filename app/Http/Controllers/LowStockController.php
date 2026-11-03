<?php

namespace App\Http\Controllers;

use App\Models\Product;

class LowStockController extends Controller
{
    public function index()
    {
        // Barang yang stoknya habis.
        $outOfStockProducts = Product::with('category')
            ->where('stock', '<=', 0)
            ->orderBy('name')
            ->paginate(10, ['*'], 'habis_page');

        // Barang yang stoknya menipis tetapi masih tersedia.
        $lowStockProducts = Product::with('category')
            ->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->orderBy('name')
            ->paginate(10, ['*'], 'menipis_page');

        // Jumlah barang untuk masing-masing kondisi.
        $totalOutOfStock = Product::where('stock', '<=', 0)->count();

        $totalLowStock = Product::where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        return view('low-stock.index', compact(
            'outOfStockProducts',
            'lowStockProducts',
            'totalOutOfStock',
            'totalLowStock'
        ));
    }
}
