<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [
        'seller_order_id', 'shipping_method_id', 'status', 'carrier_name',
        'tracking_number', 'tracking_url', 'shipped_at', 'delivered_at',
        'seller_note', 'shipping_address_snapshot', 'shipping_cost', 'currency',
    ];

    protected $casts = [
        'shipped_at' => 'datetime', // زمان ارسال
        'delivered_at' => 'datetime', // زمان تحویل
        'shipping_address_snapshot' => 'array', // تصویر ثابت نشانی مقصد
    ];

    public function sellerOrder(): BelongsTo
    {
        return $this->belongsTo(SellerOrder::class); // سفارش فروشنده مربوط به مرسوله
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class); // روش ارسال مرسوله
    }

    public function events(): HasMany
    {
        return $this->hasMany(ShipmentEvent::class)->orderBy('occurred_at'); // تاریخچه رویدادها
    }
}
