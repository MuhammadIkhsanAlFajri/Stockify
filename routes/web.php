<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LowStockController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use App\Models\StockMovement;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StockOpnameController;

// Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Semua halaman Stockify wajib login
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', function () {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');

        $totalStockIn = StockMovement::where('type', 'in')
            ->sum('quantity');

        $totalStockOut = StockMovement::where('type', 'out')
            ->sum('quantity');

        $outOfStock = Product::where('stock', '<=', 0)->count();

        $lowStock = Product::where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->count();

        $recentMovements = StockMovement::with('product')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalStock',
            'totalStockIn',
            'totalStockOut',
            'lowStock',
            'outOfStock',
            'recentMovements'
        ));
    })->name('dashboard');

    Route::resource('stock-opnames', StockOpnameController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::resource('purchases', PurchaseController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::resource('suppliers', SupplierController::class);

    // Data barang
    Route::resource('products', ProductController::class);

    // Kategori
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');

        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserManagementController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserManagementController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserManagementController::class, 'update'])
            ->name('users.update');

        Route::put('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])
            ->name('users.reset-password');

        Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])
            ->name('users.toggle-active');

        Route::get('/settings/backup', [DatabaseBackupController::class, 'index'])
            ->name('settings.backup');

        Route::post('/settings/backup', [DatabaseBackupController::class, 'store'])
            ->name('settings.backup.store');

        Route::get('/settings/backup/{filename}', [DatabaseBackupController::class, 'download'])
            ->name('settings.backup.download');

        Route::post('/settings/restore', [DatabaseBackupController::class, 'restore'])
            ->name('settings.backup.restore');
    });

    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');
    // Peringatan stok
    Route::get('/low-stock', [LowStockController::class, 'index'])
        ->name('low-stock.index');

    // Riwayat dan transaksi stok
    Route::get('/stock-movements', [StockMovementController::class, 'index'])
        ->name('stock-movements.index');

    Route::get('/stock-movements/create', [StockMovementController::class, 'create'])
        ->name('stock-movements.create');

    Route::post('/stock-movements', [StockMovementController::class, 'store'])
        ->name('stock-movements.store');

    // Laporan
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/reports/export', [ReportController::class, 'export'])
        ->name('reports.export');

    Route::get('/settings/password', [SettingController::class, 'editPassword'])
        ->name('settings.password');

    Route::put('/settings/password', [SettingController::class, 'updatePassword'])
        ->name('settings.password.update');

    // Laporan Stock Opname
    Route::get('/reports/stock-opname', [StockOpnameController::class, 'report'])
        ->name('reports.stock-opname');

    Route::get('/reports/stock-opname/export', [StockOpnameController::class, 'exportReport'])
        ->name('reports.stock-opname.export');

    Route::get('/reports/opname/export', [ReportController::class, 'exportOpname'])
        ->name('reports.opname.export');
});
