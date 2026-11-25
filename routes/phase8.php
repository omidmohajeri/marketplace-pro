<?php

use App\Http\Controllers\Admin\ShipmentEventController;
use App\Http\Controllers\Seller\ShipmentController;
use App\Http\Controllers\ShippingTrackingController;
use Illuminate\Support\Facades\Route;

// رهگیری عمومی با کد رهگیری؛ اطلاعات شخصی مقصد در خروجی نمایش داده نمی‌شود.
Route::get('/shipping/track/{trackingNumber}', [ShippingTrackingController::class, 'show'])
    ->name('shipping.track');

Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index'); // فهرست مرسوله‌های فروشنده
    Route::post('/orders/{sellerOrder}/shipment', [ShipmentController::class, 'create'])->name('shipments.create'); // ایجاد مرسوله برای سفارش
    Route::post('/shipments/{shipment}/shipped', [ShipmentController::class, 'shipped'])->name('shipments.shipped'); // ثبت کد رهگیری
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/shipments/{shipment}/events', [ShipmentEventController::class, 'store'])->name('shipments.events.store'); // ثبت دستی رویداد رهگیری
});
