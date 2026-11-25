<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerWalletTransaction extends Model
{
    protected $fillable = [
        'seller_id', 'seller_order_id', 'payout_request_id', 'type',
        'amount', 'currency', 'idempotency_key', 'reference', 'description', 'meta',
    ];

    protected $casts = ['meta' => 'array']; // تبدیل JSON به آرایه PHP

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class); // فروشنده صاحب تراکنش
    }

    public function sellerOrder(): BelongsTo
    {
        return $this->belongsTo(SellerOrder::class); // سفارش مبنای اعتبار
    }

    public function payoutRequest(): BelongsTo
    {
        return $this->belongsTo(PayoutRequest::class); // درخواست تسویه مرتبط
    }
}
