<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['seller_id','category_id','sku','slug','name','description','price','compare_at_price','stock','status','attributes','published_at'];
    protected function casts(): array
    {
        return ['name'=>'array','description'=>'array','attributes'=>'array','price'=>'integer','compare_at_price'=>'integer','stock'=>'integer','published_at'=>'datetime'];
    }
    public function seller(): BelongsTo { return $this->belongsTo(Seller::class); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function images(): HasMany { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class, 'product_id'); } // اقلام فروخته‌شده برای رتبه‌بندی محبوبیت
}
