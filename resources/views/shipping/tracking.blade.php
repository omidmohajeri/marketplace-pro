@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl p-4" dir="rtl">
    <h1 class="mb-2 text-2xl font-bold">رهگیری مرسوله</h1>
    <div class="mb-6 rounded-xl border bg-white p-4">
        <div class="text-sm text-gray-500">کد رهگیری</div>
        <div class="text-xl font-bold" dir="ltr">{{ $shipment->tracking_number }}</div>
        <p class="mt-2">وضعیت فعلی: <strong>{{ $shipment->status }}</strong></p>
        @if($shipment->tracking_url)
            <a href="{{ $shipment->tracking_url }}" target="_blank" rel="noopener" class="mt-2 inline-block text-blue-700 underline">رهگیری در وب‌سایت شرکت حمل</a>
        @endif
    </div>

    <h2 class="mb-3 text-lg font-bold">تاریخچه جابه‌جایی</h2>
    <div class="space-y-3">
        @forelse($shipment->events as $event)
            <div class="rounded-lg border bg-white p-4">
                <div class="flex flex-wrap justify-between gap-2">
                    <strong>{{ $event->title }}</strong>
                    <span class="text-sm text-gray-500">{{ optional($event->occurred_at)->format('Y/m/d H:i') }}</span>
                </div>
                <p class="mt-1 text-sm">{{ $event->description }}</p>
                @if($event->location) <p class="mt-1 text-xs text-gray-500">موقعیت: {{ $event->location }}</p> @endif
            </div>
        @empty
            <p class="text-gray-500">هنوز رویدادی برای این مرسوله ثبت نشده است.</p>
        @endforelse
    </div>
</div>
@endsection
