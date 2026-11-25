@extends('admin.layout')
@section('title','داشبورد')
@section('heading','داشبورد مدیریت')
@section('content')
<div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4">
@foreach([['کاربران',$usersCount],['محصولات فعال',$productsCount],['فروشندگان در انتظار',$pendingSellers],['سفارش‌های باز',$openOrders],['مجموع فروش',number_format($revenue)]] as [$label,$value])
<div class="bg-white border rounded-2xl p-5"><p class="text-sm text-slate-500">{{ $label }}</p><p class="text-2xl font-black mt-3">{{ $value }}</p></div>
@endforeach
</div>
<section class="bg-white border rounded-2xl p-5 mt-7">
<h2 class="font-black text-lg mb-4">آخرین سفارش‌ها</h2>
<div class="overflow-x-auto"><table class="w-full text-sm text-right">
<thead class="text-slate-500 border-b"><tr><th class="p-3">شماره</th><th class="p-3">مشتری</th><th class="p-3">مبلغ</th><th class="p-3">وضعیت</th><th class="p-3"></th></tr></thead>
<tbody>@forelse($latestOrders as $order)<tr class="border-b"><td class="p-3">{{ $order->number }}</td><td class="p-3">{{ $order->user?->name }}</td><td class="p-3">{{ number_format($order->grand_total) }}</td><td class="p-3">{{ $order->status }}</td><td class="p-3"><a class="text-rose-600" href="{{ route('admin.orders.show',$order) }}">جزئیات</a></td></tr>@empty<tr><td colspan="5" class="p-4 text-slate-500">سفارشی ثبت نشده است.</td></tr>@endforelse</tbody>
</table></div>
</section>
@endsection
