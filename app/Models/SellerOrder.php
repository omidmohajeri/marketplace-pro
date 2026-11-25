<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// مدل سفارش تفکیک‌شده یک فروشنده از سفارش اصلی مشتری.
class SellerOrder extends Model
{
    // فیلدهای قابل ذخیره‌سازی سفارش فروشنده.
    protected $fillable = [
        'order_id', 'seller_id', 'number', 'subtotal', 'commission_rate',
        'commission_amount', 'seller_amount', 'status',
    ];

    // تبدیل مبالغ به عدد اعشاری با دقت دو رقم.
    protected $casts = [
        'subtotal' => 'decimal:2',
        'commission_rate' => 'decimal:4',
        'commission_amount' => 'decimal:2',
        'seller_amount' => 'decimal:2',
    ];

    // سفارش اصلی که این سفارش فروشنده از آن ایجاد شده است.
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // فروشگاهی که مسئول تأمین این اقلام است.
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    // اقلام سفارش اصلی که به این فروشنده اختصاص یافته‌اند.
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(OrderItem::class, 'seller_order_items')->withTimestamps();
    }
}
