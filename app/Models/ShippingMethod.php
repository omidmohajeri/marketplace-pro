<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    protected $fillable = [
        'name', 'code', 'description', 'base_cost', 'cost_per_kg',
        'free_shipping_threshold', 'estimated_min_days', 'estimated_max_days',
        'is_active', 'meta',
    ];

    protected $casts = [
        'is_active' => 'boolean', // تبدیل وضعیت فعال به boolean
        'meta' => 'array', // خواندن تنظیمات JSON به شکل آرایه
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class); // مرسوله‌های استفاده‌کننده از این روش
    }
}
