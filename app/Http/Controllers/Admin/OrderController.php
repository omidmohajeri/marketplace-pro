<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// این کنترلر سفارش‌ها را برای مدیر فهرست و وضعیت آن‌ها را مدیریت می‌کند.
class OrderController extends Controller
{
    // فهرست سفارش‌ها را همراه با اطلاعات مشتری و صفحه‌بندی نشان می‌دهد.
    public function index(Request $request)
    {
        $orders = Order::with('user')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('number'), fn ($q) => $q->where('number', 'like', '%' . $request->string('number') . '%'))
            ->latest()->paginate(25)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    // جزئیات یک سفارش را نمایش می‌دهد.
    public function show(Order $order)
    {
        $order->load(['user', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    // وضعیت سفارش را با فهرست مجاز وضعیت‌ها تغییر می‌دهد.
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'])],
        ]);

        // وضعیت پرداخت از طریق این فرم جعل نمی‌شود؛ وضعیت پرداخت باید از نتیجه تأییدشده درگاه بیاید.
        abort_if(in_array($order->status, ['refunded', 'cancelled'], true) && $data['status'] !== $order->status, 422, 'سفارش نهایی‌شده نیازمند فرایند اصلاح جداگانه است.');

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'وضعیت سفارش به‌روزرسانی شد.');
    }
}
