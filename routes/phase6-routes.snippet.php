<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// آغاز پرداخت فقط برای کاربر واردشده و مالک سفارش مجاز است.
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/orders/{order}/pay', [PaymentController::class, 'checkout'])->name('payments.checkout');
});

// callback با URL امضاشده محافظت می‌شود؛ CSRF برای بازگشت بیرونی در این مسیر لازم نیست.
Route::get('/payments/callback/{order}', [PaymentController::class, 'callback'])
    ->middleware('signed')
    ->name('payments.callback');
