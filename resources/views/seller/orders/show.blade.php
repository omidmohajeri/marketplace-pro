@extends('seller.layout')
@section('title', 'جزئیات سفارش فروشنده')
@section('content')
{{-- نمایش شماره و وضعیت سفارش مستقل فروشگاه --}}
<h1 class="mb-2 text-2xl font-bold">سفارش {{ $sellerOrder->number }}</h1>
<p class="mb-6 text-slate-500">وضعیت: {{ $sellerOrder->status }} | سفارش اصلی: {{ $sellerOrder->order->number ?? $sellerOrder->order_id }}</p>
{{-- اقلام این فروشنده به همراه جمع مالی --}}
<div class="overflow-x-auto rounded-2xl bg-white p-5 shadow-sm">
<table class="w-full text-right text-sm"><thead class="border-b"><tr><th class="p-3">کالا</th><th class="p-3">تعداد</th><th class="p-3">قیمت واحد</th><th class="p-3">جمع</th></tr></thead><tbody>
@foreach($sellerOrder->items as $item)
<tr class="border-b last:border-0"><td class="p-3">{{ is_array($item->product->name ?? null) ? ($item->product->name['fa'] ?? '') : ($item->product->name ?? 'کالای حذف‌شده') }}</td><td class="p-3">{{ $item->quantity }}</td><td class="p-3">{{ number_format($item->unit_price) }}</td><td class="p-3">{{ number_format($item->quantity * $item->unit_price) }}</td></tr>
@endforeach
</tbody></table>
{{-- خلاصه کمیسیون و مبلغ خالص فروشگاه --}}
<div class="mt-6 grid gap-3 sm:grid-cols-3">
<div class="rounded-xl bg-slate-50 p-4">جمع کالاها: <strong>{{ number_format($sellerOrder->subtotal) }}</strong></div>
<div class="rounded-xl bg-slate-50 p-4">کمیسیون: <strong>{{ number_format($sellerOrder->commission_amount) }}</strong></div>
<div class="rounded-xl bg-blue-50 p-4">سهم فروشگاه: <strong>{{ number_format($sellerOrder->seller_amount) }}</strong></div>
</div>
<a href="{{ route('seller.orders.index') }}" class="mt-5 inline-block text-blue-700">بازگشت</a>
</div>
@endsection
