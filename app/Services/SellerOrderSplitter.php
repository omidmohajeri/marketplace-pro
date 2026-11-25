<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SellerOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

// این سرویس سفارش اصلی را به سفارش‌های فروشنده‌ها تقسیم و سهم مالی را محاسبه می‌کند.
class SellerOrderSplitter
{
    // سفارش فروشنده‌ها را از روی اقلام ذخیره‌شده سفارش اصلی می‌سازد.
    public function split(Order $order): array
    {
        // اقلام و محصولات برای شناسایی فروشنده هر کالا بارگذاری می‌شوند.
        $items = $order->items()->with('product.seller')->get();

        // سفارش بدون قلم قابل تقسیم نیست.
        if ($items->isEmpty()) {
            throw new RuntimeException('سفارش اصلی هیچ قلمی برای تفکیک ندارد.');
        }

        // تمام عملیات در یک تراکنش انجام می‌شود تا داده نیمه‌کاره باقی نماند.
        return DB::transaction(function () use ($order, $items) {
            $created = [];

            // اقلام بر اساس فروشنده محصول دسته‌بندی می‌شوند.
            foreach ($items->groupBy(fn (OrderItem $item) => $item->product?->seller_id ?? 0) as $sellerId => $sellerItems) {
                // کالا بدون فروشنده در این گردش چندفروشنده‌ای پذیرفته نمی‌شود.
                if ((int) $sellerId < 1) {
                    throw new RuntimeException('یکی از کالاهای سفارش فروشنده معتبری ندارد.');
                }

                // درصد کمیسیون از فروشگاه خوانده و در سفارش ثابت می‌شود.
                $seller = $sellerItems->first()->product->seller;
                if (!$seller || $seller->status !== 'active') {
                    throw new RuntimeException('یکی از فروشگاه‌های سفارش فعال نیست.');
                }

                // جمع ناخالص فقط از اقلام همین فروشنده محاسبه می‌شود.
                $subtotal = $sellerItems->sum(fn ($item) => (float) $item->quantity * (float) $item->unit_price);

                // درصد کمیسیون در بازه صفر تا صد محدود می‌شود.
                $rate = (float) ($seller->commission_rate ?? 0);
                if ($rate < 0 || $rate > 100) {
                    throw new RuntimeException('درصد کمیسیون فروشگاه معتبر نیست.');
                }

                // کمیسیون و مبلغ خالص با گرد کردن دو رقم اعشار محاسبه می‌شوند.
                $commission = round($subtotal * $rate / 100, 2);
                $sellerAmount = round($subtotal - $commission, 2);

                // ساخت سفارش فرعی برای فروشنده با وضعیت پرداخت‌نشده.
                $sellerOrder = SellerOrder::firstOrCreate(
                    ['order_id' => $order->id, 'seller_id' => $seller->id],
                    [
                        'number' => 'SO-'.now()->format('ymd').'-'.Str::upper(Str::random(8)),
                        'subtotal' => $subtotal,
                        'commission_rate' => $rate,
                        'commission_amount' => $commission,
                        'seller_amount' => $sellerAmount,
                        'status' => 'pending_payment',
                    ]
                );

                // اقلام سفارش اصلی به سفارش فرعی متصل می‌شوند.
                foreach ($sellerItems as $item) {
                    $sellerOrder->items()->syncWithoutDetaching([$item->id]);
                }

                // سفارش فروشنده برای نمایش یا گزارش بعدی به خروجی افزوده می‌شود.
                $created[] = $sellerOrder->load('items');
            }

            // فهرست سفارش‌های تفکیک‌شده را برمی‌گرداند.
            return $created;
        });
    }
}
