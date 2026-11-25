<?php

namespace App\Contracts;

use App\Models\PaymentAttempt;
use Illuminate\Http\Request;

// قرارداد مشترک تمام درگاه‌ها برای شروع و اعتبارسنجی پرداخت.
interface PaymentGateway
{
    // درخواست پرداخت را به درگاه می‌فرستد و URL هدایت کاربر را برمی‌گرداند.
    public function createPayment(PaymentAttempt $attempt, string $callbackUrl): string;

    // callback را با API یا امضای معتبر درگاه بررسی می‌کند و نتیجه استاندارد می‌دهد.
    public function verifyCallback(Request $request, PaymentAttempt $attempt): array;
}
