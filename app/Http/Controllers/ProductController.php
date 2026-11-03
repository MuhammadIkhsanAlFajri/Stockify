<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\ActivityLog;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'search'));
    }

    private function logActivity(
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $properties = []
    ): void {
        ActivityLog::create([
            'user_id' => request()->user()->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'properties' => $properties ?: null,
        ]);
    }

    public function create()
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:products,code'],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        $product = Product::create($validated);

        $this->logActivity(
            'product_created',
            'Menambahkan barang: ' . $product->name,
            Product::class,
            $product->id,
            ['code' => $product->code]
        );

        return redirect()->route('products.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'code')->ignore($product->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
        ]);

        $oldValues = $product->only([
            'code',
            'name',
            'category_id',
            'stock',
            'unit',
            'location',
            'purchase_price',
            'selling_price',
            'minimum_stock',
        ]);

        $product->update($validated);

        $this->logActivity(
            'product_updated',
            'Memperbarui barang: ' . $product->name,
            Product::class,
            $product->id,
            [
                'before' => $oldValues,
                'after' => $product->only(array_keys($oldValues)),
            ]
        );

        return redirect()->route('products.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Request $request, Product $product)
    {
        abort_unless(request()->user()?->role === 'admin', 403);

        $hasHistory = $product->stockMovements()->exists();

        if ($hasHistory && !$request->boolean('confirm_history_delete')) {
            return redirect()
                ->route('products.index')
                ->with(
                    'delete_warning',
                    'Barang "' . $product->name . '" memiliki riwayat transaksi stok. ' .
                        'Menghapus barang ini juga akan menghapus riwayat transaksinya. ' .
                        'Silakan lakukan konfirmasi tambahan.'
                )
                ->with('pending_delete_id', $product->id);
        }

        $productName = $product->name;
        $productId = $product->id;
        $hadHistory = $hasHistory;

        $product->delete();

        $this->logActivity(
            'product_deleted',
            'Menghapus barang: ' . $productName,
            Product::class,
            $productId,
            ['had_stock_history' => $hadHistory]
        );

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                $hasHistory
                    ? 'Barang dan riwayat transaksi stoknya berhasil dihapus.'
                    : 'Barang berhasil dihapus.'
            );
    }
}
