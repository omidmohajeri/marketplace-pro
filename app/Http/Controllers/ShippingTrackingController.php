<?php

namespace App\Http\Controllers;

use App\Models\Shipment;

class ShippingTrackingController extends Controller
{
    public function show(string $trackingNumber)
    {
        $shipment = Shipment::with(['events', 'shippingMethod', 'sellerOrder'])
            ->where('tracking_number', $trackingNumber)->firstOrFail(); // یافتن مرسوله با کد رهگیری

        return view('shipping.tracking', compact('shipment')); // نمایش تاریخچه عمومی بدون اطلاعات خصوصی مقصد
    }
}
