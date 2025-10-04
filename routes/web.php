<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionCostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

    Route::middleware(['role:admin,staff'])->group(function () {
        Route::resource('orders', OrderController::class);
    });

    Route::middleware(['role:admin,owner'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
        Route::get('/reports/invoices', [ReportController::class, 'invoices'])->name('reports.invoices');
        Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductController::class);
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
    Route::resource('orders', OrderController::class);

    Route::patch('/orders/{order}/price', [OrderController::class, 'updatePrice'])->name('orders.updatePrice');
    Route::patch('/orders/{order}/update-custom-product', [OrderController::class, 'updateCustomProduct'])->name('orders.updateCustomProduct');

    Route::delete('/orders/{order}/boms/{orderBom}', [OrderController::class, 'destroyBomItem'])->name('orders.boms.destroy');

    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::post('/orders/{order}/boms', [OrderController::class, 'storeBomItem'])->name('orders.boms.store');
    Route::delete('/boms/{orderBom}', [OrderController::class, 'destroyBomItem'])->name('orders.boms.destroy'); // Disederhanakan

    Route::post('/orders/{order}/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::post('/orders/{order}/purchases/multiple', [PurchaseController::class, 'storeMultiple'])->name('purchases.storeMultiple');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy');
    Route::post('/orders/{order}/purchases/upload-receipt', [PurchaseController::class, 'uploadReceipt'])->name('purchases.uploadReceipt');

    Route::post('/orders/{order}/costs', [ProductionCostController::class, 'store'])->name('costs.store');
    Route::delete('/costs/{productionCost}', [ProductionCostController::class, 'destroy'])->name('costs.destroy'); // Nama parameter disamakan

    Route::post('/orders/{order}/incomes', [IncomeController::class, 'store'])->name('incomes.store');
    Route::delete('/incomes/{income}', [IncomeController::class, 'destroy'])->name('incomes.destroy');

    Route::middleware(['role:admin,finance,staff'])->group(function () {
        Route::post('/orders/{order}/generate-invoice', [InvoiceController::class, 'generate'])->name('invoices.generate');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::patch('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.updateStatus');
        Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
        Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
