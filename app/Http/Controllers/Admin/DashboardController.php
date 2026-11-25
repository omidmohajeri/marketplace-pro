<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;

// داشبورد مدیر کل، آمار کلی فروشگاه را از دیتابیس جمع‌آوری می‌کند.
class DashboardController extends Controller
{
    // صفحه اصلی پنل مدیریت را همراه با آمار و آخرین سفارش‌ها نمایش می‌دهد.
    public function index()
    {
        // تعداد کل کاربران ثبت‌نام‌شده.
        $usersCount = User::count();

        // تعداد کالاهای فعال قابل نمایش در فروشگاه.
        $productsCount = Product::where('status', 'active')->count();

        // تعداد فروشندگان در انتظار بررسی.
        $pendingSellers = Seller::where('status', 'pending')->count();

        // تعداد سفارش‌هایی که هنوز نهایی نشده‌اند.
        $openOrders = Order::whereIn('status', ['pending', 'paid', 'processing'])->count();

        // مجموع مبالغ سفارش‌های پرداخت‌شده؛ واحد پول همان واحد ذخیره‌شده در دیتابیس است.
        $revenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])->sum('grand_total');

        // آخرین سفارش‌ها برای دسترسی سریع مدیر.
        $latestOrders = Order::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'usersCount', 'productsCount', 'pendingSellers', 'openOrders', 'revenue', 'latestOrders'
        ));
    }
}
