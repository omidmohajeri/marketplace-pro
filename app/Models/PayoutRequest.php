<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayoutRequest extends Model
{
    protected $fillable = [
        'seller_id', 'amount', 'currency', 'status', 'bank_account_name',
        'bank_name', 'iban', 'seller_note', 'admin_note', 'reviewed_by',
        'reviewed_at', 'transferred_at', 'transfer_reference', 'debit_idempotency_key',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime', // زمان بررسی مدیر
        'transferred_at' => 'datetime', // زمان ثبت انتقال وجه
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class); // فروشنده درخواست‌دهنده
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by'); // مدیر بررسی‌کننده
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(SellerWalletTransaction::class); // تراکنش‌های مرتبط با درخواست
    }
}
