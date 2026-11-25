<?php

namespace App\Providers;

use App\Contracts\PaymentGateway;
use App\Payments\ExampleGateway;
use Illuminate\Support\ServiceProvider;

// این Provider قرارداد درگاه را به Adapter فعال متصل می‌کند.
class PaymentServiceProvider extends ServiceProvider
{
    // اتصال singleton برای استفاده مشترک سرویس‌های پرداخت.
    public function register(): void
    {
        $this->app->singleton(PaymentGateway::class, function ($app) {
            // تا زمان افزودن Adapter رسمی، نمونه امن و غیرفعال استفاده می‌شود.
            return $app->make(ExampleGateway::class);
        });

        // نام کوتاه برای سازگاری با کنترلر این فاز.
        $this->app->alias(PaymentGateway::class, 'payments.gateway');
    }
}
