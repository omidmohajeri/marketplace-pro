<?php
// اعتبارسنجی و محاسبه تخفیف در سمت سرور؛ مبلغ ارسالی مرورگر قابل اعتماد نیست
namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponService
{
    public function quote(string $code, int $userId, float $subtotal): array
    {
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [mb_strtoupper(trim($code))])->first();
        if (!$coupon || !$coupon->is_active) $this->invalid();
        $now = now();
        if ($coupon->starts_at && $now->lt($coupon->starts_at)) $this->invalid();
        if ($coupon->expires_at && $now->gt($coupon->expires_at)) $this->invalid();
        if ($subtotal < (float)$coupon->minimum_order_amount) {
            throw ValidationException::withMessages(['coupon' => 'مبلغ سبد خرید به حداقل لازم برای این کد نمی‌رسد.']);
        }
        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) $this->invalid();
        if ($coupon->usage_limit_per_user !== null) {
            $count = CouponUsage::where('coupon_id',$coupon->id)->where('user_id',$userId)->count();
            if ($count >= $coupon->usage_limit_per_user) $this->invalid();
        }
        $discount = in_array($coupon->type, ['percentage', 'percent'], true)
            ? $subtotal * ((float)$coupon->value / 100)
            : (float)$coupon->value;
        if ($coupon->maximum_discount_amount !== null) $discount = min($discount, (float)$coupon->maximum_discount_amount);
        $discount = min($subtotal, max(0, round($discount, 2)));
        return ['coupon'=>$coupon, 'discount_amount'=>$discount, 'payable_amount'=>max(0, round($subtotal-$discount, 2))];
    }

    // پس از ساخته‌شدن سفارش، مصرف کوپن در تراکنش دیتابیس ثبت شود
    public function recordUsage(int $couponId, int $userId, int $orderId, float $discount): void
    {
        DB::transaction(function () use ($couponId,$userId,$orderId,$discount) {
            $coupon = Coupon::whereKey($couponId)->lockForUpdate()->firstOrFail();
            if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) $this->invalid();
            $usage = CouponUsage::firstOrNew(['coupon_id'=>$couponId, 'order_id'=>$orderId]);
            if (!$usage->exists) {
                $usage->fill(['user_id'=>$userId, 'discount_amount'=>$discount]);
                $usage->save();
                $coupon->increment('used_count'); // فقط مصرف تازه، شمارنده را افزایش می‌دهد
            }
        });
    }

    private function invalid(): never
    {
        throw ValidationException::withMessages(['coupon' => 'کد تخفیف نامعتبر، غیرفعال یا خارج از شرایط استفاده است.']);
    }
}