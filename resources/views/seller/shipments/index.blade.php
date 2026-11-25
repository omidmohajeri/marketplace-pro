@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-6xl p-4" dir="rtl">
    <h1 class="mb-5 text-2xl font-bold">مرسوله‌های من</h1>
    @if(session('success')) <div class="mb-4 rounded bg-green-100 p-3">{{ session('success') }}</div> @endif
    <div class="space-y-4">
        @forelse($shipments as $shipment)
            <div class="rounded-xl border bg-white p-4 shadow-sm">
                <div class="flex flex-wrap justify-between gap-2">
                    <strong>سفارش فروشنده شماره {{ $shipment->seller_order_id }}</strong>
                    <span>{{ $shipment->status }}</span>
                </div>
                <p class="mt-2 text-sm text-gray-600">شرکت حمل: {{ $shipment->carrier_name ?? 'تعیین نشده' }}</p>
                <p class="text-sm text-gray-600">کد رهگیری: {{ $shipment->tracking_number ?? 'هنوز ثبت نشده' }}</p>

                @if(in_array($shipment->status, ['preparing', 'pending']))
                    <form method="POST" action="{{ route('seller.shipments.shipped', $shipment) }}" class="mt-4 grid gap-2 md:grid-cols-3">
                        @csrf
                        <input name="carrier_name" placeholder="نام شرکت حمل" class="rounded border p-2">
                        <input name="tracking_number" required placeholder="کد رهگیری" class="rounded border p-2">
                        <input name="tracking_url" type="url" placeholder="لینک رهگیری (اختیاری)" class="rounded border p-2">
                        <button class="rounded bg-slate-900 p-2 text-white md:col-span-3">ثبت تحویل به حمل‌کننده</button>
                    </form>
                @endif
                @if($shipment->tracking_number)
                    <a class="mt-3 inline-block text-blue-700 underline" href="{{ route('shipping.track', $shipment->tracking_number) }}">مشاهده رهگیری</a>
                @endif
            </div>
        @empty
            <div class="rounded border p-6 text-center text-gray-500">هنوز مرسوله‌ای ثبت نشده است.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $shipments->links() }}</div>
</div>
@endsection
