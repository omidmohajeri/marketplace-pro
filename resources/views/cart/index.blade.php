@extends('layouts.app')
@section('title','سبد خرید')
@section('content')
<h1 class="text-2xl font-black mb-6">سبد خرید</h1>
<div class="bg-white rounded-2xl border p-5 space-y-4">
@php($total = 0)
@forelse($cart->items as $item)
@php($line = $item->product->price * $item->quantity)
@php($total += $line)
<div class="flex flex-wrap items-center justify-between gap-4 border-b pb-4">
<div><h2 class="font-bold">{{ data_get($item->product->name,app()->getLocale()) }}</h2><p class="text-rose-600 font-bold mt-1">{{ number_format($item->product->price) }} ریال</p></div>
<div class="flex items-center gap-2">
<form method="POST" action="{{ route('cart.update',$item) }}">@csrf @method('PATCH')
<input name="quantity" type="number" min="1" max="20" value="{{ $item->quantity }}" class="w-20 border rounded-lg p-2">
<button class="bg-slate-900 text-white rounded-lg px-3 py-2">بروزرسانی</button></form>
<form method="POST" action="{{ route('cart.remove',$item) }}">@csrf @method('DELETE')
<button class="text-rose-600 px-2 py-2">حذف</button></form>
</div>
</div>
@empty <p class="text-slate-500">سبد خرید خالی است.</p> @endforelse
<div class="text-xl font-black text-left">جمع: {{ number_format($total) }} ریال</div>
<p class="text-sm text-slate-500">ثبت سفارش و پرداخت در فاز بعدی اضافه می‌شود.</p>
</div>
@endsection
