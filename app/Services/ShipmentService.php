<?php

namespace App\Services;

use App\Models\SellerOrder;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ShipmentService
{
    /**
     * ساخت مرسوله برای سفارش فروشنده؛ فروشنده فقط سفارش خودش را می‌تواند ارسال کند.
     */
    public function create(SellerOrder $sellerOrder, array $data, int $sellerId): Shipment
    {
        return DB::transaction(function () use ($sellerOrder, $data, $sellerId) {
            $order = SellerOrder::query()->lockForUpdate()->findOrFail($sellerOrder->id);

            if ((int) $order->seller_id !== $sellerId) {
                abort(403); // جلوگیری از دسترسی به سفارش فروشنده دیگر
            }

            if (!in_array($order->status, ['paid', 'payment_confirmed', 'processing', 'ready_to_ship'], true)) {
                throw ValidationException::withMessages(['order' => 'این سفارش هنوز آماده ارسال نیست.']);
            }

            if (Shipment::where('seller_order_id', $order->id)->exists()) {
                throw ValidationException::withMessages(['shipment' => 'برای این سفارش قبلاً مرسوله ثبت شده است.']);
            }

            $shipment = Shipment::create([
                'seller_order_id' => $order->id,
                'shipping_method_id' => $data['shipping_method_id'] ?? null,
                'status' => 'preparing',
                'carrier_name' => $data['carrier_name'] ?? null,
                'seller_note' => $data['seller_note'] ?? null,
                'shipping_address_snapshot' => $data['shipping_address_snapshot'] ?? null,
                'shipping_cost' => (int) ($data['shipping_cost'] ?? 0),
                'currency' => $order->currency ?? 'IRR',
            ]);

            $this->event($shipment, 'preparing', 'آماده‌سازی مرسوله', 'مرسوله برای بسته‌بندی آماده شد.', $sellerId); // ثبت اولین رویداد

            return $shipment;
        }, 3);
    }

    /**
     * ثبت ارسال و کد رهگیری توسط فروشنده.
     */
    public function markShipped(Shipment $shipment, array $data, int $sellerId): Shipment
    {
        return DB::transaction(function () use ($shipment, $data, $sellerId) {
            $locked = Shipment::query()->lockForUpdate()->with('sellerOrder')->findOrFail($shipment->id);

            if ((int) $locked->sellerOrder->seller_id !== $sellerId) {
                abort(403); // مالکیت سفارش کنترل می‌شود
            }

            if (!in_array($locked->status, ['preparing', 'pending'], true)) {
                throw ValidationException::withMessages(['status' => 'وضعیت فعلی اجازه ثبت ارسال را نمی‌دهد.']);
            }

            $locked->update([
                'status' => 'shipped',
                'carrier_name' => $data['carrier_name'] ?? $locked->carrier_name,
                'tracking_number' => $data['tracking_number'],
                'tracking_url' => $data['tracking_url'] ?? null,
                'shipped_at' => now(),
            ]);

            $this->event($locked, 'shipped', 'تحویل به شرکت حمل', 'مرسوله به شرکت حمل‌کننده تحویل داده شد.', $sellerId);

            return $locked->refresh();
        }, 3);
    }

    /**
     * ثبت رویداد رهگیری؛ در نسخه فعلی دستی و توسط مدیر انجام می‌شود.
     */
    public function addTrackingEvent(Shipment $shipment, array $data, int $userId): ShipmentEvent
    {
        return DB::transaction(function () use ($shipment, $data, $userId) {
            $locked = Shipment::query()->lockForUpdate()->findOrFail($shipment->id);

            $allowed = ['in_transit', 'delivered', 'failed', 'returned'];
            if (!in_array($data['status'], $allowed, true)) {
                throw ValidationException::withMessages(['status' => 'وضعیت رهگیری معتبر نیست.']);
            }

            $locked->update([
                'status' => $data['status'],
                'delivered_at' => $data['status'] === 'delivered' ? now() : $locked->delivered_at,
            ]);

            return $this->event(
                $locked,
                $data['status'],
                $data['title'],
                $data['description'] ?? null,
                $userId,
                $data['location'] ?? null
            );
        }, 3);
    }

    private function event(Shipment $shipment, string $status, string $title, ?string $description, ?int $userId, ?string $location = null): ShipmentEvent
    {
        return ShipmentEvent::create([
            'shipment_id' => $shipment->id,
            'status' => $status,
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'occurred_at' => now(),
            'created_by' => $userId,
        ]); // ذخیره تاریخچه مستقل از وضعیت جاری مرسوله
    }
}
