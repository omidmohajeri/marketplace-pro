<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// این کنترلر درخواست‌های عضویت فروشندگان و وضعیت حساب آن‌ها را مدیریت می‌کند.
class SellerController extends Controller
{
    // فهرست فروشندگان با امکان فیلتر وضعیت را نمایش می‌دهد.
    public function index(Request $request)
    {
        $sellers = Seller::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()->paginate(25)->withQueryString();

        return view('admin.sellers.index', compact('sellers'));
    }

    // وضعیت فروشنده و درصد کمیسیون را به‌روزرسانی می‌کند.
    public function update(Request $request, Seller $seller)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'active', 'suspended'])],
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $seller->update($data);

        return back()->with('success', 'وضعیت فروشنده ذخیره شد.');
    }
}
