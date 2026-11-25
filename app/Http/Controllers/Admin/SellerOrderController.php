<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use Illuminate\Http\Request;

// این کنترلر گزارش مالی سفارش‌های تفکیک‌شده را در اختیار مدیر قرار می‌دهد.
class SellerOrderController extends Controller
{
    // فهرست سفارش‌های فروشنده‌ها را برای مدیر نمایش می‌دهد.
    public function index(Request $request)
    {
        $query = SellerOrder::with(['seller', 'order'])->latest();

        // فیلتر اختیاری فروشگاه برای بررسی عملکرد یک فروشنده.
        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->integer('seller_id'));
        }

        // فیلتر اختیاری وضعیت سفارش فرعی.
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        // آمار مالی بر اساس رکوردهای فیلترشده محاسبه می‌شود.
        $summary = [
            'gross' => (clone $query)->sum('subtotal'),
            'commission' => (clone $query)->sum('commission_amount'),
            'seller_due' => (clone $query)->sum('seller_amount'),
        ];

        $sellerOrders = $query->paginate(20)->withQueryString();

        return view('admin.seller-orders.index', compact('sellerOrders', 'summary'));
    }
}
