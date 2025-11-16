<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('login_view');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

        /** Kelola Menu (AdminController) */
        Route::get('/admin/menu', [AdminController::class, 'menuIndex'])->name('menu.index');
        Route::get('/admin/menu/create', [AdminController::class, 'menuCreate'])->name('menu.create');
        Route::post('/admin/menu', [AdminController::class, 'menuStore'])->name('menu.store');
        Route::get('/admin/menu/{idmenu}/edit', [AdminController::class, 'menuEdit'])->name('menu.edit');
        Route::put('/admin/menu/{idmenu}', [AdminController::class, 'menuUpdate'])->name('menu.update');
        Route::delete('/admin/menu/{idmenu}', [AdminController::class, 'menuDestroy'])->name('menu.destroy');

        /** Kelola Meja (AdminController) */
        Route::get('/admin/meja', [AdminController::class, 'mejaIndex'])->name('meja.index');
        Route::get('/admin/meja/create', [AdminController::class, 'mejaCreate'])->name('meja.create');
        Route::post('/admin/meja', [AdminController::class, 'mejaStore'])->name('meja.store');
        Route::get('/admin/meja/{idmeja}/edit', [AdminController::class, 'mejaEdit'])->name('meja.edit');
        Route::put('/admin/meja/{idmeja}', [AdminController::class, 'mejaUpdate'])->name('meja.update');
        Route::delete('/admin/meja/{idmeja}', [AdminController::class, 'mejaDestroy'])->name('meja.destroy');
    });

    // Waiter
    Route::middleware(['role:waiter'])->group(function () {
        Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter.dashboard');
        // Kelola Order
        Route::get('/waiter/orders', [WaiterController::class, 'orderIndex'])->name('order.index');
        Route::get('/waiter/orders/create', [WaiterController::class, 'orderCreate'])->name('order.create');
        Route::post('/waiter/orders', [WaiterController::class, 'orderStore'])->name('order.store');
        Route::delete('/waiter/orders/{id}', [WaiterController::class, 'orderCancel'])->name('order.cancel');
        // Laporan
        Route::get('/waiter/laporan', [WaiterController::class, 'laporan'])->name('waiter.laporan');
        Route::get('/waiter/laporan/pdf', [WaiterController::class, 'laporanPdf'])->name('waiter.laporan.pdf');
        // Kelola menu
        Route::get('/waiter/menu', [WaiterController::class, 'menuIndex'])->name('waiter.menu.index');
        Route::get('/waiter/menu/create', [WaiterController::class, 'menuCreate'])->name('waiter.menu.create');
        Route::post('/waiter/menu', [WaiterController::class, 'menuStore'])->name('waiter.menu.store');
        Route::get('/waiter/menu/{idmenu}/edit', [WaiterController::class, 'menuEdit'])->name('waiter.menu.edit');
        Route::put('/waiter/menu/{idmenu}', [WaiterController::class, 'menuUpdate'])->name('waiter.menu.update');
        Route::delete('/waiter/menu/{idmenu}', [WaiterController::class, 'menuDestroy'])->name('waiter.menu.destroy');
    });

    // Kasir
    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.dashboard');

        // Kelola Transaksi
        Route::get('/kasir/transaksi', [KasirController::class, 'transaksiIndex'])->name('kasir.transaksi.index');
        Route::get('/kasir/transaksi/{idpesanan}/bayar', [KasirController::class, 'transaksiPayForm'])->name('kasir.transaksi.pay.form');
        Route::post('/kasir/transaksi/{idpesanan}/bayar', [KasirController::class, 'transaksiPayStore'])->name('kasir.transaksi.pay.store');

        // Laporan
        Route::get('/kasir/laporan', [KasirController::class, 'laporan'])->name('kasir.laporan');
        Route::get('/kasir/laporan/pdf', [KasirController::class, 'laporanPdf'])->name('kasir.laporan.pdf');
        // ⬇️ Struk PDF
        Route::get('/kasir/struk/{idtransaksi}.pdf', [KasirController::class, 'receiptPdf'])->name('kasir.receipt.pdf');
        // ⬇️ Struk HTML
        Route::get('/kasir/struk/{idtransaksi}/print', [KasirController::class, 'receiptPrint'])->name('kasir.receipt.print');
    });

    // Owner
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/owner', [OwnerController::class, 'index'])->name('owner.dashboard');

        // Laporan Owner
        Route::get('/owner/laporan',     [OwnerController::class, 'laporan'])->name('owner.laporan');
        Route::get('/owner/laporan/pdf', [OwnerController::class, 'laporanPdf'])->name('owner.laporan.pdf');
    });
});