@extends('layouts.app')
@section('title', data_get($product->name,app()->getLocale()))
@section('content')
<div class="grid md:grid-cols-2 gap-8 bg-white rounded-3xl p-6 md:p-10">
<div class="aspect-square bg-slate-100 rounded-2xl flex items-center justify-center text-8xl text-slate-300">▧</div>
<div>
<p class="text-sm text-slate-400">{{ $product->category?->name[app()->getLocale()] ?? '' }}</p>
<h1 class="text-3xl font-black mt-3">{{ data_get($product->name,app()->getLocale()) }}</h1>
<p class="text-slate-500 leading-8 mt-5">{{ data_get($product->description,app()->getLocale()) }}</p>
<p class="text-rose-600 text-2xl font-black mt-8">{{ number_format($product->price) }} ریال</p>
<p class="text-sm mt-3">موجودی: {{ $product->stock }}</p>
<button disabled class="mt-8 bg-slate-300 text-slate-600 rounded-xl px-8 py-3 font-bold cursor-not-allowed">سبد خرید در فاز بعدی فعال می‌شود</button>
<p class="text-xs text-slate-400 mt-3">فروشنده: {{ $product->seller?->store_name ?? 'فروشگاه اصلی' }}</p>
</div></div>
@endsection
