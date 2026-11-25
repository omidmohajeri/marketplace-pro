@extends('layouts.app')
@section('title', __('خانه'))
@section('content')
<section class="rounded-3xl bg-gradient-to-l from-slate-900 to-rose-700 text-white p-10 md:p-16">
  <p class="text-rose-200 mb-3">MARKETPLACE PRO</p>
  <h1 class="text-3xl md:text-5xl font-black leading-tight">تجربه خریدی ساده، سریع و مطمئن</h1>
  <p class="mt-4 text-slate-200">محصولات متنوع از فروشگاه اصلی و فروشندگان منتخب</p>
  <a href="{{ route('products.index') }}" class="inline-block mt-7 bg-white text-slate-900 rounded-xl px-6 py-3 font-bold">مشاهده فروشگاه</a>
</section>
<section class="mt-12">
  <div class="flex justify-between items-center mb-5"><h2 class="text-2xl font-black">{{ __('محصولات منتخب') }}</h2><a href="{{ route('products.index') }}" class="text-rose-600">همه محصولات ←</a></div>
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
  @forelse($products as $product)
    <a href="{{ route('products.show',$product->slug) }}" class="bg-white rounded-2xl p-4 border hover:shadow-lg transition">
      <div class="aspect-square rounded-xl bg-slate-100 flex items-center justify-center text-slate-300 text-5xl">▧</div>
      <h3 class="font-bold mt-4 min-h-12">{{ data_get($product->name, app()->getLocale(), data_get($product->name,'fa')) }}</h3>
      <p class="text-rose-600 font-black mt-3">{{ number_format($product->price) }} <small>ریال</small></p>
    </a>
  @empty <p>هنوز محصولی ثبت نشده است.</p> @endforelse
  </div>
</section>
@endsection
