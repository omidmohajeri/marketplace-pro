@extends('seller.layout')
@section('title', 'داشبورد')
@section('content')
{{-- سربرگ داشبورد فروشنده --}}
<div class="mb-7">
    <h1 class="text-2xl font-bold">سلام، {{ $seller->store_name }}</h1>
    <p class="mt-1 text-slate-500">نمای کلی عملکرد فروشگاه شما</p>
</div>
{{-- کارت‌های آماری --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">تعداد کالاها</p><p class="mt-2 text-3xl font-bold">{{ number_format($productCount) }}</p></div>
    <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">کالاهای کم‌موجودی</p><p class="mt-2 text-3xl font-bold">{{ number_format($lowStockCount) }}</p></div>
    <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">درآمد سفارش‌های معتبر</p><p class="mt-2 text-3xl font-bold">{{ number_format($revenue) }}</p></div>
    <div class="rounded-2xl bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">وضعیت فروشگاه</p><p class="mt-2 text-xl font-bold">{{ $seller->status }}</p></div>
</div>
{{-- جدول سفارش‌های اخیر --}}
<section class="mt-8 rounded-2xl bg-white p-5 shadow-sm">
    <h2 class="mb-4 text-lg font-bold">آخرین سفارش‌های مرتبط</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-right text-sm">
            <thead class="border-b text-slate-500"><tr><th class="p-3">شماره</th><th class="p-3">خریدار</th><th class="p-3">وضعیت</th><th class="p-3">جزئیات</th></tr></thead>
            <tbody>
            @forelse($recentOrders as $order)
                <tr class="border-b last:border-0"><td class="p-3">{{ $order->number ?? $order->id }}</td><td class="p-3">{{ $order->user->name ?? '—' }}</td><td class="p-3">{{ $order->status }}</td><td class="p-3"><a class="text-blue-700" href="{{ route('seller.orders.show', $order) }}">مشاهده</a></td></tr>
            @empty
                <tr><td colspan="4" class="p-5 text-center text-slate-500">هنوز سفارشی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
