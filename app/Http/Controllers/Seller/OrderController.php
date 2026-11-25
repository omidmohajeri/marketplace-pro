<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use Illuminate\Http\Request;

// این کنترلر فقط سفارش‌های تفکیک‌شده متعلق به فروشنده واردشده را ارائه می‌کند.
class OrderController extends Controller
{
    // فهرست سفارش‌های مستقل فروشنده را نمایش می‌دهد.
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403);

        // محدودیت seller_id مانع مشاهده سفارش سایر فروشگاه‌ها می‌شود.
        $orders = SellerOrder::where('seller_id', $seller->id)
            ->with('order')
            ->latest()
            ->paginate(15);

        return view('seller.orders.index', compact('orders'));
    }

    // جزئیات یک سفارش فرعی را با بررسی مالکیت نمایش می‌دهد.
    public function show(Request $request, SellerOrder $sellerOrder)
    {
        $seller = $request->user()->seller;
        abort_unless($seller && (int) $sellerOrder->seller_id === (int) $seller->id, 404);

        // فقط اقلام متصل به سفارش همین فروشنده بارگذاری می‌شوند.
        $sellerOrder->load(['order', 'items.product']);

        return view('seller.orders.show', compact('sellerOrder'));
    }
}
