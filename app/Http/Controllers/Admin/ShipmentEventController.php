<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Services\ShipmentService;
use Illuminate\Http\Request;

class ShipmentEventController extends Controller
{
    public function store(Request $request, Shipment $shipment, ShipmentService $service)
    {
        $data = $request->validate([
            'status' => ['required', 'in:in_transit,delivered,failed,returned'], // وضعیت جدید مرسوله
            'title' => ['required', 'string', 'max:150'], // عنوان رویداد
            'description' => ['nullable', 'string', 'max:2000'], // توضیح رویداد
            'location' => ['nullable', 'string', 'max:200'], // موقعیت مرسوله
        ]);

        $service->addTrackingEvent($shipment, $data, (int) $request->user()->id); // ثبت رویداد رهگیری
        return back()->with('success', 'وضعیت رهگیری به‌روزرسانی شد.');
    }
}
