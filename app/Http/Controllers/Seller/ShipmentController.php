<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerOrder;
use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $seller = $request->user()->seller; // فروشنده جاری
        abort_unless($seller, 403); // فقط حساب فروشنده مجاز است

        $shipments = Shipment::with('sellerOrder')
            ->whereHas('sellerOrder', fn ($q) => $q->where('seller_id', $seller->id))
            ->latest()->paginate(20); // فقط مرسوله‌های همین فروشنده

        return view('seller.shipments.index', compact('shipments'));
    }

    public function create(Request $request, SellerOrder $sellerOrder, ShipmentService $service)
    {
        $data = $request->validate([
            'shipping_method_id' => ['nullable', 'exists:shipping_methods,id'], // روش ارسال انتخابی
            'carrier_name' => ['nullable', 'string', 'max:120'], // شرکت حمل‌کننده
            'seller_note' => ['nullable', 'string', 'max:1000'], // یادداشت بسته‌بندی
            'shipping_cost' => ['nullable', 'integer', 'min:0'], // هزینه ارسال که باید از quote سرور بیاید
        ]);

        $seller = $request->user()->seller; // مالک سفارش
        abort_unless($seller, 403); // کنترل نقش
        $service->create($sellerOrder, $data, $seller->id); // ایجاد مرسوله با کنترل مالکیت

        return redirect()->route('seller.shipments.index')->with('success', 'مرسوله ثبت شد.');
    }

    public function shipped(Request $request, Shipment $shipment, ShipmentService $service)
    {
        $data = $request->validate([
            'tracking_number' => ['required', 'string', 'max:150'], // کد رهگیری شرکت حمل
            'carrier_name' => ['nullable', 'string', 'max:120'], // نام شرکت حمل‌کننده
            'tracking_url' => ['nullable', 'url', 'max:500'], // لینک رهگیری
        ]);

        $seller = $request->user()->seller; // فروشنده جاری
        abort_unless($seller, 403); // کنترل نقش
        $service->markShipped($shipment, $data, $seller->id); // ثبت ارسال با کنترل مالکیت

        return back()->with('success', 'کد رهگیری ثبت شد.');
    }
}
