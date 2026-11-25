<?php
// این مسیرها را با گروه‌های احراز هویت و دسترسی مدیر پروژه ادغام کنید
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/cart/coupon', [CouponController::class,'apply'])->name('cart.coupon.apply'); // اعمال کد
    Route::delete('/cart/coupon', [CouponController::class,'remove'])->name('cart.coupon.remove'); // حذف کد
});
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::resource('coupons', AdminCouponController::class)->except(['show']); // CRUD کمپین‌ها
});