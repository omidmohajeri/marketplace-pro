@extends('layouts.app')
@section('title', __('فروشگاه'))
@section('content')
<h1 class="text-2xl font-black mb-6">{{ __('فروشگاه') }}</h1>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
@forelse($products as $product)
<a href="{{ route('products.show',$product->slug) }}" class="bg-white rounded-2xl p-4 border hover:shadow-lg">
<div class="aspect-square rounded-xl bg-slate-100 flex items-center justify-center text-5xl text-slate-300">▧</div>
<h2 class="font-bold mt-3">{{ data_get($product->name,app()->getLocale(),data_get($product->name,'fa')) }}</h2>
<p class="text-rose-600 font-black mt-2">{{ number_format($product->price) }} ریال</p>
</a>
@empty <p>محصولی پیدا نشد.</p> @endforelse
</div>
<div class="mt-8">{{ $products->links() }}</div>
@endsection
