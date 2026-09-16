<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BarcodeScanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingItemController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OutgoingItemController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Barcode Scanning API Endpoints
    Route::get('/api/items/scan', [BarcodeScanController::class, 'scan'])->name('api.items.scan');
    Route::post('/api/items/quick-store', [BarcodeScanController::class, 'quickStore'])->name('api.items.quick-store');

    // Item View (Accessible by Admin, Gudang, Pimpinan)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/print-barcodes', [ItemController::class, 'printBarcodes'])->name('items.print-barcodes');
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show')->where('id', '[0-9]+');

    // Reports (Accessible by Admin, Gudang, Pimpinan)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/incoming', [ReportController::class, 'incoming'])->name('incoming');
        Route::get('/outgoing', [ReportController::class, 'outgoing'])->name('outgoing');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });

    // Admin & Gudang Routes (Barcode POS Cashier Scanner Transaksi & Stock Opname)
    Route::middleware('role:admin,gudang')->group(function () {
        Route::get('/pos/scan', [PosController::class, 'index'])->name('pos.scan');
        Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');

        Route::resource('incoming', IncomingItemController::class)->except(['edit', 'update']);
        Route::resource('outgoing', OutgoingItemController::class)->except(['edit', 'update']);
        Route::resource('adjustments', StockAdjustmentController::class)->except(['edit', 'update', 'destroy']);
    });

    // Admin Only Routes (Manage Users, Suppliers, Categories, CRUD Items)
    Route::middleware('role:admin')->group(function () {
        // Backup Database Download (Admin Only - berisi seluruh data termasuk hash password)
        Route::get('/backup/download', [BackupController::class, 'download'])->name('backup.download');

        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Item CRUD extensions for Admin
        Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
        Route::post('/items', [ItemController::class, 'store'])->name('items.store');
        Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit')->where('id', '[0-9]+');
        Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update')->where('id', '[0-9]+');
        Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy')->where('id', '[0-9]+');
    });
});
