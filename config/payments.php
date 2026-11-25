<?php

// تنظیمات مرکزی پرداخت از متغیرهای محیطی خوانده می‌شوند.
return [
    // نام Adapter فعال که در Service Provider به قرارداد PaymentGateway متصل می‌شود.
    'default' => env('PAYMENT_GATEWAY', 'example'),

    // واحد پول پیش‌فرض پروژه؛ باید با واحد مورد انتظار درگاه یکسان باشد.
    'currency' => env('PAYMENT_CURRENCY', 'IRR'),

    // کلید محرمانه فقط در سرور نگهداری شود و هرگز به مرورگر ارسال نشود.
    'merchant_id' => env('PAYMENT_MERCHANT_ID'),

    // آدرس API درگاه از تنظیمات محیطی دریافت می‌شود.
    'api_url' => env('PAYMENT_API_URL'),

    // زمان انقضای لینک بازگشت پرداخت به دقیقه.
    'callback_ttl_minutes' => (int) env('PAYMENT_CALLBACK_TTL', 30),
];
