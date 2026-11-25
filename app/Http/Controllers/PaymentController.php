<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Services\PaymentService;
use App\Services\SellerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use RuntimeException;

// کنترلر آغاز پرداخت و دریافت بازگشت درگاه را مدیریت می‌کند.
class PaymentController extends Controller
{
    // کاربر مالک سفارش را به درگاه فعال هدایت می‌کند.
    public function checkout(Request $request, Order $order, PaymentService $payments)
    {
        // فقط مالک سفارش اجازه پرداخت آن را دارد.
        abort_unless((int) $order->user_id === (int) $request->user()->id, 404);

        // نام درگاه از تنظیمات سرور خوانده می‌شود، نه از ورودی کاربر.
        $gatewayName = config('payments.default');
        $gateway = app('payments.gateway');

        // callback امضاشده موقتاً برای همین سفارش ساخته می‌شود.
        $callbackUrl = URL::temporarySignedRoute('payments.callback', now()->addMinutes(30), ['order' => $order->id]);

        // سرویس تلاش پرداخت را ذخیره و URL امن درگاه را ایجاد می‌کند.
        $payment = $payments->start($order, $gateway, $gatewayName, $callbackUrl);

        return redirect()->away($payment['redirect_url']);
    }

    // نتیجه برگشتی درگاه را بررسی و سفارش را در صورت تأیید پرداخت‌شده می‌کند.
    public function callback(Request $request, Order $order, PaymentService $payments)
    {
        // امضای URL از دستکاری پارامترهای callback جلوگیری می‌کند.
        abort_unless($request->hasValidSignature(), 403);

        // آخرین تلاش در انتظار برای همین سفارش دریافت می‌شود.
        $attempt = PaymentAttempt::where('order_id', $order->id)
            ->whereIn('status', ['initiated', 'pending'])
            ->latest()
            ->firstOrFail();

        // Adapter درگاه باید تراکنش را با سرویس رسمی درگاه verify کند.
        $gateway = app('payments.gateway');
        $success = $payments->complete($request, $attempt, $gateway, app(SellerWalletService::class));

        return $success
            ? redirect()->route('orders.show', $order)->with('success', 'پرداخت با موفقیت تأیید شد.')
            : redirect()->route('orders.show', $order)->with('error', 'پرداخت تأیید نشد؛ وضعیت سفارش تغییر نکرد.');
    }
}
