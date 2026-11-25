<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// مدل هر بار تلاش برای پرداخت یک سفارش.
class PaymentAttempt extends Model
{
    // فیلدهای مجاز برای ثبت تلاش پرداخت.
    protected $fillable = [
        'order_id', 'gateway', 'status', 'amount', 'currency',
        'authority', 'reference', 'gateway_payload', 'paid_at',
    ];

    // تبدیل نوع داده‌های مبلغ، پاسخ درگاه و تاریخ پرداخت.
    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_payload' => 'array',
        'paid_at' => 'datetime',
    ];

    // سفارش مرتبط با این پرداخت.
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
