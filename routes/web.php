<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ValuationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'search'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::post('/search', [SearchController::class, 'search'])->name('search.post');

Route::get('/value-a-domain', [ValuationController::class, 'index'])->name('valuation');
Route::post('/value-a-domain', [ValuationController::class, 'analyze'])->name('valuation.analyze');

Route::middleware('auth')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/sales', [AdminController::class, 'sales'])->name('admin.sales');
        Route::get('/admin/sales/add', [AdminController::class, 'addSale'])->name('admin.add-sale');
        Route::post('/admin/sales/store', [AdminController::class, 'storeSale'])->name('admin.store-sale');
        Route::get('/admin/sales/bulk-paste', [AdminController::class, 'bulkPaste'])->name('admin.bulk-paste');
        Route::post('/admin/sales/bulk-store', [AdminController::class, 'storeBulkPaste'])->name('admin.bulk-store');
        Route::get('/admin/sales/{id}/edit', [AdminController::class, 'editSale'])->name('admin.edit-sale');
        Route::put('/admin/sales/{id}', [AdminController::class, 'updateSale'])->name('admin.update-sale');
        Route::delete('/admin/sales/{id}', [AdminController::class, 'destroySale'])->name('admin.destroy-sale');
        Route::post('/admin/setup', [AdminController::class, 'setupDatabase'])->name('admin.setup');
        Route::post('/admin/seed', [AdminController::class, 'seedData'])->name('admin.seed');

        Route::get('/admin/populations', [AdminController::class, 'populations'])->name('admin.populations');
        Route::post('/admin/populations/store', [AdminController::class, 'storePopulation'])->name('admin.populations.store');
        Route::post('/admin/populations/bulk', [AdminController::class, 'bulkPopulation'])->name('admin.populations.bulk');
        Route::post('/admin/populations/seed', [AdminController::class, 'seedPopulations'])->name('admin.populations.seed');
        Route::delete('/admin/populations/{id}', [AdminController::class, 'destroyPopulation'])->name('admin.populations.destroy');
        Route::post('/admin/populations/seed-states', [AdminController::class, 'seedStatePopulations'])->name('admin.populations.seed-states');

        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    });

    Route::get('/dashboard', function () {
        if (Auth::user() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
