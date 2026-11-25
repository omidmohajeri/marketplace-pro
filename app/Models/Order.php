<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['user_id','number','status','currency','subtotal','discount_total','shipping_total','grand_total','shipping_address','billing_address','placed_at'];
    protected function casts(): array { return ['shipping_address'=>'array','billing_address'=>'array','placed_at'=>'datetime']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
