@extends('admin.layout')
@section('title', 'سفارش‌های فروشنده‌ها')
@section('content')
{{-- عنوان گزارش تجمیعی سفارش‌های فروشندگان --}}
<h1 class="mb-6 text-2xl font-bold">گزارش سفارش‌های فروشندگان</h1>
{{-- خلاصه مبلغ ناخالص، کمیسیون و سهم فروشندگان --}}
<div class="mb-6 grid gap-4 sm:grid-cols-3">
<div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">جمع مبلغ کالاها</p><p class="mt-2 text-2xl font-bold">{{ number_format($summary['gross']) }}</p></div>
<div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">جمع کمیسیون</p><p class="mt-2 text-2xl font-bold">{{ number_format($summary['commission']) }}</p></div>
<div class="rounded-xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">جمع سهم فروشندگان</p><p class="mt-2 text-2xl font-bold">{{ number_format($summary['seller_due']) }}</p></div>
</div>
{{-- جدول سفارش‌های مستقل فروشگاه‌ها --}}
<div class="overflow-x-auto rounded-xl bg-white p-4 shadow-sm">
<table class="w-full text-right text-sm"><thead class="border-b"><tr><th class="p-3">شماره سفارش</th><th class="p-3">فروشگاه</th><th class="p-3">مبلغ کالا</th><th class="p-3">کمیسیون</th><th class="p-3">سهم فروشنده</th><th class="p-3">وضعیت</th></tr></thead><tbody>
@forelse($sellerOrders as $sellerOrder)
<tr class="border-b last:border-0"><td class="p-3">{{ $sellerOrder->number }}</td><td class="p-3">{{ $sellerOrder->seller->store_name ?? $sellerOrder->seller_id }}</td><td class="p-3">{{ number_format($sellerOrder->subtotal) }}</td><td class="p-3">{{ number_format($sellerOrder->commission_amount) }}</td><td class="p-3">{{ number_format($sellerOrder->seller_amount) }}</td><td class="p-3">{{ $sellerOrder->status }}</td></tr>
@empty
<tr><td colspan="6" class="p-5 text-center text-slate-500">سفارشی برای نمایش وجود ندارد.</td></tr>
@endforelse
</tbody></table>
<div class="mt-4">{{ $sellerOrders->links() }}</div>
</div>
@endsection
