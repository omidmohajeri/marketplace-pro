<?php

use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\InventoryController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\ProductController;
use Illuminate\Support\Facades\Route;

// تمام مسیرهای این گروه فقط برای کاربر واردشده با نقش فروشنده در دسترس‌اند.
Route::middleware(['web', 'auth', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // مدیریت کالاهای فروشگاه
        Route::resource('products', ProductController::class)->except(['show']);

        // مدیریت موجودی محصولات
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::patch('/inventory/{product}', [InventoryController::class, 'update'])->name('inventory.update');
        // مسیرهای سفارش در فاز پنجم با SellerOrder جایگزین شده‌اند.
});
