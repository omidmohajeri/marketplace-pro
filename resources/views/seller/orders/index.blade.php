@extends('seller.layout')
@section('title', 'سفارش‌های فروشگاه')
@section('content')
{{-- عنوان صفحه سفارش‌های اختصاصی فروشنده --}}
<h1 class="mb-6 text-2xl font-bold">سفارش‌های فروشگاه من</h1>
{{-- جدول سفارش‌های فرعی و سهم مالی فروشنده --}}
<div class="overflow-x-auto rounded-2xl bg-white p-4 shadow-sm">
<table class="w-full text-right text-sm">
<thead class="border-b text-slate-500"><tr><th class="p-3">شماره</th><th class="p-3">سفارش اصلی</th><th class="p-3">مبلغ کالاها</th><th class="p-3">کمیسیون</th><th class="p-3">سهم فروشگاه</th><th class="p-3">وضعیت</th><th class="p-3">جزئیات</th></tr></thead>
<tbody>
@forelse($orders as $sellerOrder)
<tr class="border-b last:border-0">
<td class="p-3">{{ $sellerOrder->number }}</td><td class="p-3">{{ $sellerOrder->order->number ?? $sellerOrder->order_id }}</td>
<td class="p-3">{{ number_format($sellerOrder->subtotal) }}</td><td class="p-3">{{ number_format($sellerOrder->commission_amount) }} ({{ $sellerOrder->commission_rate }}%)</td>
<td class="p-3 font-semibold">{{ number_format($sellerOrder->seller_amount) }}</td><td class="p-3">{{ $sellerOrder->status }}</td>
<td class="p-3"><a class="text-blue-700" href="{{ route('seller.orders.show', $sellerOrder) }}">مشاهده</a></td>
</tr>
@empty
<tr><td colspan="7" class="p-5 text-center text-slate-500">هنوز سفارش تفکیک‌شده‌ای ندارید.</td></tr>
@endforelse
</tbody></table>
<div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
