@extends('admin.layout')
@section('title','سفارش‌ها')
@section('heading','مدیریت سفارش‌ها')
@section('content')
<form class="mb-5 flex flex-wrap gap-2"><input name="number" value="{{ request('number') }}" placeholder="شماره سفارش" class="border rounded-xl p-2"><select name="status" class="border rounded-xl p-2"><option value="">همه وضعیت‌ها</option>@foreach(['pending','paid','processing','shipped','delivered','cancelled','refunded'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ $s }}</option>@endforeach</select><button class="bg-slate-900 text-white rounded-xl px-4">جست‌وجو</button></form>
<div class="bg-white border rounded-2xl overflow-x-auto"><table class="w-full text-sm text-right">
<thead class="bg-slate-50"><tr><th class="p-3">شماره</th><th class="p-3">مشتری</th><th class="p-3">مبلغ</th><th class="p-3">وضعیت</th><th class="p-3"></th></tr></thead>
<tbody>@forelse($orders as $order)<tr class="border-t"><td class="p-3">{{ $order->number }}</td><td class="p-3">{{ $order->user?->name }}</td><td class="p-3">{{ number_format($order->grand_total) }} {{ $order->currency }}</td><td class="p-3">{{ $order->status }}</td><td class="p-3"><a class="text-rose-600" href="{{ route('admin.orders.show',$order) }}">جزئیات</a></td></tr>@empty<tr><td colspan="5" class="p-4">سفارشی وجود ندارد.</td></tr>@endforelse</tbody></table></div>
<div class="mt-5">{{ $orders->links() }}</div>
@endsection
