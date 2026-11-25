<?php
// مدل کمپین و کد تخفیف
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = ['code','name','description','type','value','minimum_order_amount','maximum_discount_amount','minimum_order','max_discount','usage_limit','usage_limit_per_user','used_count','starts_at','expires_at','is_active'];
    protected $casts = ['value'=>'decimal:2','minimum_order_amount'=>'decimal:2','maximum_discount_amount'=>'decimal:2','starts_at'=>'datetime','expires_at'=>'datetime','is_active'=>'boolean'];
    public function usages(): HasMany { return $this->hasMany(CouponUsage::class); } // سوابق استفاده
}