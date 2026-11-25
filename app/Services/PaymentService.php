<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\PaymentAttempt;
use App\Models\SellerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

// سرویس مرکزی پرداخت؛ جزئیات هر شرکت پرداخت در Adapter آن قرار می‌گیرد.
class PaymentService
{
    // ساخت تلاش پرداخت برای سفارش و درخواست آدرس پرداخت از درگاه.
    public function start(Order $order, PaymentGateway $gateway, string $gatewayName, string $callbackUrl): array
    {
        // سفارش فقط در وضعیت قابل پرداخت می‌تواند وارد درگاه شود.
        if (!in_array($order->status, ['pending', 'pending_payment', 'unpaid'], true)) {
            throw new RuntimeException('وضعیت سفارش اجازه پرداخت مجدد نمی‌دهد.');
        }

        // مبلغ از دیتابیس خوانده می‌شود و از ورودی مرورگر پذیرفته نمی‌شود.
        $amount = (float) ($order->grand_total ?? 0);
        if ($amount <= 0) {
            throw new RuntimeException('مبلغ سفارش معتبر نیست.');
        }

        // تلاش پرداخت ابتدا در وضعیت initiated ذخیره می‌شود.
        $attempt = PaymentAttempt::create([
            'order_id' => $order->id,
            'gateway' => $gatewayName,
            'status' => 'initiated',
            'amount' => $amount,
            'currency' => $order->currency ?? config('payments.currency', 'IRR'),
        ]);

        try {
            // درگاه باید URL واقعی و معتبر پرداخت را ایجاد کند.
            $redirectUrl = $gateway->createPayment($attempt, $callbackUrl);
            if (!filter_var($redirectUrl, FILTER_VALIDATE_URL) || !in_array(parse_url($redirectUrl, PHP_URL_SCHEME), ['https'], true)) {
                throw new RuntimeException('درگاه آدرس HTTPS معتبر برنگرداند.');
            }

            // URL فقط در پاسخ همان درخواست استفاده می‌شود و توکن محرمانه در آن ذخیره نمی‌شود.
            return ['attempt' => $attempt, 'redirect_url' => $redirectUrl];
        } catch (\Throwable $exception) {
            // خطای شروع پرداخت در دیتابیس ثبت می‌شود.
            $attempt->update(['status' => 'failed']);
            throw $exception;
        }
    }

    // callback درگاه را اعتبارسنجی کرده و فقط در صورت تأیید واقعی سفارش را paid می‌کند.
    public function complete(Request $request, PaymentAttempt $attempt, PaymentGateway $gateway, SellerWalletService $wallets): bool
    {
        // نتیجه درگاه خارج از تراکنش دیتابیس بررسی می‌شود تا قفل طولانی ایجاد نشود.
        $result = $gateway->verifyCallback($request, $attempt);

        // فقط پاسخ استاندارد موفق با مبلغ و شناسه مرجع قابل قبول است.
        if (($result['success'] ?? false) !== true
            || (float) ($result['amount'] ?? 0) !== (float) $attempt->amount
            || empty($result['reference'])) {
            $attempt->update(['status' => 'failed']);
            return false;
        }

        // قفل ردیف مانع ثبت هم‌زمان callbackهای تکراری می‌شود.
        return DB::transaction(function () use ($attempt, $result, $wallets) {
            $locked = PaymentAttempt::whereKey($attempt->id)->lockForUpdate()->firstOrFail();

            // callback تکراری برای پرداختی که قبلاً ثبت شده است idempotent است.
            if ($locked->status === 'paid') {
                return true;
            }

            // تلاش ناموفق یا منقضی‌شده دوباره paid نمی‌شود.
            if (!in_array($locked->status, ['initiated', 'pending'], true)) {
                return false;
            }

            $order = Order::whereKey($locked->order_id)->lockForUpdate()->firstOrFail();

            // اگر سفارش قبلاً پرداخت شده، تلاش جدید نباید دوباره آن را تسویه کند.
            if ($order->status === 'paid') {
                $locked->update(['status' => 'duplicate', 'reference' => $result['reference']]);
                return false;
            }

            // ثبت شناسه مرجع و زمان تأیید پرداخت.
            $locked->update([
                'status' => 'paid',
                'reference' => (string) $result['reference'],
                'gateway_payload' => ['verified' => true],
                'paid_at' => now(),
            ]);

            // تغییر وضعیت سفارش فقط پس از تأیید درگاه انجام می‌شود.
            $order->update(['status' => 'paid']);

            // سفارش‌های فروشنده نیز پرداخت‌شده می‌شوند و سهم خالص به دفترکل کیف پول منتقل می‌شود.
            $sellerOrders = SellerOrder::where('order_id', $order->id)->lockForUpdate()->get();
            foreach ($sellerOrders as $sellerOrder) {
                $sellerOrder->update(['status' => 'paid']);
                $wallets->creditPaidSellerOrder($sellerOrder->refresh());
            }

            // نتیجه موفق به کنترلر برگردانده می‌شود.
            return true;
        });
    }
}
