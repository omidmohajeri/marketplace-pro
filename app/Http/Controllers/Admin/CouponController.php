<?php
// مدیریت کمپین‌ها و کدهای تخفیف توسط مدیر
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index() { return view('admin.coupons.index', ['coupons'=>Coupon::latest()->paginate(20)]); }
    public function create() { return view('admin.coupons.form', ['coupon'=>new Coupon()]); }
    public function store(Request $request) { return $this->save($request, new Coupon()); }
    public function edit(Coupon $coupon) { return view('admin.coupons.form', compact('coupon')); }
    public function update(Request $request, Coupon $coupon) { return $this->save($request, $coupon); }
    public function destroy(Coupon $coupon) { $coupon->update(['is_active'=>false]); return redirect()->route('admin.coupons.index')->with('success','کد غیرفعال شد.'); }

    private function save(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'=>['required','string','max:64',Rule::unique('coupons','code')->ignore($coupon->id)],
            'name'=>'required|string|max:255','description'=>'nullable|string',
            'type'=>'required|in:percentage,fixed','value'=>'required|numeric|min:0.01',
            'minimum_order_amount'=>'nullable|numeric|min:0','maximum_discount_amount'=>'nullable|numeric|min:0',
            'usage_limit'=>'nullable|integer|min:1','usage_limit_per_user'=>'nullable|integer|min:1',
            'starts_at'=>'nullable|date','expires_at'=>'nullable|date|after_or_equal:starts_at','is_active'=>'sometimes|boolean',
        ]);
        $data['code'] = mb_strtoupper(trim($data['code']));
        // کنترل سقف درصد پیش از نگاشت به enum قدیمی دیتابیس
        if ($data['type'] === 'percentage' && (float) $data['value'] > 100) {
            return back()->withErrors(['value' => 'درصد تخفیف نمی‌تواند بیشتر از ۱۰۰ باشد.'])->withInput();
        }
        if ($data['type'] === 'percentage') $data['type'] = 'percent'; // سازگاری با enum فاز اول
        $data['minimum_order'] = (int) round($data['minimum_order_amount'] ?? 0); // نگاشت ستون قدیمی
        $data['max_discount'] = isset($data['maximum_discount_amount']) ? (int) round($data['maximum_discount_amount']) : null; // نگاشت سقف قدیمی // یکسان‌سازی حروف کد
        unset($data['minimum_order_amount'], $data['maximum_discount_amount']);
        $coupon->fill($data)->save();
        return redirect()->route('admin.coupons.index')->with('success','کمپین ذخیره شد.');
    }
}