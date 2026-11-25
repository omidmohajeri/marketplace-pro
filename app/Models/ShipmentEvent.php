<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentEvent extends Model
{
    protected $fillable = [
        'shipment_id', 'status', 'title', 'description',
        'location', 'occurred_at', 'created_by', 'meta',
    ];

    protected $casts = [
        'occurred_at' => 'datetime', // زمان واقعی وقوع رویداد
        'meta' => 'array', // داده تکمیلی رویداد
    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class); // مرسوله والد رویداد
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by'); // کاربر ثبت‌کننده
    }
}
