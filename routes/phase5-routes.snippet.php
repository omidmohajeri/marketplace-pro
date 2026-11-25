<?php

use App\Http\Controllers\Admin\SellerOrderController as AdminSellerOrderController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use Illuminate\Support\Facades\Route;

// مسیرهای سفارش فروشنده فقط برای نقش فروشنده مجاز هستند.
Route::middleware(['web', 'auth', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        // این مسیرها جایگزین مسیرهای قدیمی سفارش فروشنده در فاز چهارم می‌شوند.
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{sellerOrder}', [SellerOrderController::class, 'show'])->name('orders.show');
    });

// مسیر گزارش مالی سفارش‌های فروشنده فقط برای مدیر است.
Route::middleware(['web', 'auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/seller-orders', [AdminSellerOrderController::class, 'index'])->name('seller-orders.index');
    });
