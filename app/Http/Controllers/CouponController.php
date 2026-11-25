<?php
// API اعمال کد تخفیف روی سبد؛ قیمت نهایی فقط از داده‌های سمت سرور محاسبه می‌شود
namespace App\Http\Controllers;

use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request, CouponService $service)
    {
        $data = $request->validate(['code'=>'required|string|max:64']);
        // این پیاده‌سازی فرض می‌کند سبد خرید در session نگهداری می‌شود
        $subtotal = (float) collect(session('cart', []))->sum(fn($item) => ((float)($item['price'] ?? 0)) * ((int)($item['quantity'] ?? 0)));
        $quote = $service->quote($data['code'], (int)$request->user()->id, $subtotal);
        session(['coupon'=>['id'=>$quote['coupon']->id,'code'=>$quote['coupon']->code,'discount_amount'=>$quote['discount_amount']]]);
        return back()->with('success', 'کد تخفیف اعمال شد.');
    }

    public function remove()
    {
        session()->forget('coupon'); // حذف کوپن فعال از سبد
        return back()->with('success', 'کد تخفیف حذف شد.');
    }
}