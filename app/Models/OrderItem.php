<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product_id','seller_id','product_name','sku','quantity','unit_price','line_total'];
    protected function casts(): array { return ['product_name'=>'array','quantity'=>'integer','unit_price'=>'integer','line_total'=>'integer']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}
