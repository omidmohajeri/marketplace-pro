<?php

use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Seller\WalletController;
use Illuminate\Support\Facades\Route;

// این مسیرها را در گروه middleware و prefix متناسب با پروژه اصلی قرار دهید.
Route::middleware(['auth', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index'); // نمایش کیف پول فروشنده
    Route::post('/wallet/payout', [WalletController::class, 'requestPayout'])->name('wallet.payout'); // ثبت درخواست تسویه
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index'); // صف درخواست‌های تسویه
    Route::post('/payouts/{payout}/approve', [PayoutController::class, 'approve'])->name('payouts.approve'); // تأیید اداری
    Route::post('/payouts/{payout}/reject', [PayoutController::class, 'reject'])->name('payouts.reject'); // رد درخواست
    Route::post('/payouts/{payout}/transferred', [PayoutController::class, 'transferred'])->name('payouts.transferred'); // ثبت انتقال واقعی
});
