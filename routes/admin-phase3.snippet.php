<?php

// این مسیرها را در routes/web.php اضافه کنید؛ alias نقش باید در bootstrap/app.php ثبت شده باشد.
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // مدیریت کالاهای فروشگاه.
    Route::resource('products', AdminProductController::class)->except(['show']);

    // مدیریت دسته‌بندی‌ها.
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // بررسی و فعال/معلق‌کردن فروشندگان.
    Route::get('/sellers', [AdminSellerController::class, 'index'])->name('sellers.index');
    Route::patch('/sellers/{seller}', [AdminSellerController::class, 'update'])->name('sellers.update');

    // مشاهده سفارش‌ها و تغییر وضعیت عملیاتی آن‌ها.
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});
