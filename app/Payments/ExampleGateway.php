<?php

namespace App\Payments;

use App\Contracts\PaymentGateway;
use App\Models\PaymentAttempt;
use Illuminate\Http\Request;
use RuntimeException;

// نمونه Adapter عمداً پرداخت جعلی انجام نمی‌دهد؛ تا زمان اتصال درگاه واقعی خطا می‌دهد.
class ExampleGateway implements PaymentGateway
{
    // بدون اطلاعات رسمی پذیرنده، درخواست پرداخت ساخته نمی‌شود.
    public function createPayment(PaymentAttempt $attempt, string $callbackUrl): string
    {
        throw new RuntimeException('درگاه واقعی پیکربندی نشده است؛ اطلاعات پذیرنده و API رسمی را تنظیم کنید.');
    }

    // نتیجه callback بدون تأیید API رسمی هرگز موفق تلقی نمی‌شود.
    public function verifyCallback(Request $request, PaymentAttempt $attempt): array
    {
        throw new RuntimeException('تأیید تراکنش نیازمند Adapter رسمی درگاه پرداخت است.');
    }
}
