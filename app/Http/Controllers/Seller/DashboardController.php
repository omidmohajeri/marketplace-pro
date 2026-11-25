<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // داشبورد اختصاصی فروشنده را با آمار همان فروشگاه نمایش می‌دهد.
    public function index(Request $request)
    {
        // فروشنده به‌وسیله حساب کاربری واردشده پیدا می‌شود.
        $seller = $request->user()->seller;

        // اگر حساب کاربری هنوز فروشگاه نداشته باشد، دسترسی داده نمی‌شود.
        abort_unless($seller, 403, 'برای این حساب فروشگاهی ثبت نشده است.');

        // تعداد کالاها و کالاهای کم‌موجودی فقط برای همین فروشنده محاسبه می‌شود.
        $productCount = Product::where('seller_id', $seller->id)->count();
        $lowStockCount = Product::where('seller_id', $seller->id)->where('stock', '<=', 5)->count();

        // سفارش‌های دارای حداقل یک قلم از محصولات این فروشنده شمرده می‌شوند.
        $orderQuery = Order::whereHas('items', function ($query) use ($seller) {
            $query->whereHas('product', fn ($productQuery) => $productQuery->where('seller_id', $seller->id));
        });

        // درآمد تقریبی از ردیف‌های سفارش متعلق به محصولات فروشنده محاسبه می‌شود.
        $revenue = DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('products.seller_id', $seller->id)
            ->whereIn('orders.status', ['paid', 'processing', 'shipped', 'delivered'])
            ->sum(DB::raw('order_items.quantity * order_items.unit_price'));

        // جدیدترین سفارش‌های مرتبط با فروشنده برای نمایش سریع دریافت می‌شوند.
        $recentOrders = $orderQuery->with(['user', 'items.product'])->latest()->take(8)->get();

        // آمار و فهرست سفارش‌ها به نمای داشبورد فرستاده می‌شود.
        return view('seller.dashboard', compact('seller', 'productCount', 'lowStockCount', 'revenue', 'recentOrders'));
    }
}
