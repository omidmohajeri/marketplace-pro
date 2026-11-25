<?php

namespace App\Services;

use App\Models\ShippingMethod;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ShippingQuoteService
{
    /**
     * محاسبه نرخ روش‌های فعال ارسال.
     * weightKg و subtotal باید از داده معتبر سفارش و اقلام سرور محاسبه شوند، نه از ورودی قابل اعتمادنشده مرورگر.
     */
    public function quotes(float $weightKg, int $subtotal, string $currency = 'IRR'): Collection
    {
        if ($weightKg < 0 || $subtotal < 0) {
            throw ValidationException::withMessages(['shipping' => 'وزن یا مبلغ سفارش معتبر نیست.']);
        }

        return ShippingMethod::query()->where('is_active', true)->get()->map(function (ShippingMethod $method) use ($weightKg, $subtotal, $currency) {
            $free = $method->free_shipping_threshold !== null
                && $subtotal >= (int) $method->free_shipping_threshold; // بررسی شرط ارسال رایگان

            $billableWeight = (int) ceil($weightKg); // گرد کردن وزن به کیلوگرم کامل
            $cost = $free ? 0 : ((int) $method->base_cost + ($billableWeight * (int) $method->cost_per_kg)); // محاسبه هزینه پایه و وزنی

            return [
                'id' => $method->id, // شناسه روش ارسال
                'name' => $method->name, // نام روش ارسال
                'code' => $method->code, // کد داخلی روش
                'cost' => $cost, // هزینه محاسبه‌شده
                'currency' => $currency, // ارز هزینه
                'estimated_min_days' => $method->estimated_min_days, // حداقل زمان تخمینی
                'estimated_max_days' => $method->estimated_max_days, // حداکثر زمان تخمینی
                'is_free' => $free, // وضعیت ارسال رایگان
            ];
        });
    }
}
