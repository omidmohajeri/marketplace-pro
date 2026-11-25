{{-- فرم ساده جست‌وجو و فهرست نتایج --}}
@extends('layouts.app')
@section('content')
<div dir="rtl" class="container">
<form method="GET" action="{{ route('products.search') }}">
<input name="q" value="{{ request('q') }}" placeholder="نام کالا یا برند">
<input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="حداقل قیمت">
<input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="حداکثر قیمت">
<label><input type="checkbox" name="in_stock" value="1" @checked(request('in_stock'))> فقط موجود</label>
<select name="sort"><option value="newest">جدیدترین</option><option value="price_asc">ارزان‌ترین</option><option value="price_desc">گران‌ترین</option><option value="popular">پرفروش‌ترین</option></select>
<button>جست‌وجو</button></form>
<div class="grid">@foreach($products as $product)<article><a href="{{ route('products.show',$product->slug) }}">{{ is_array($product->name) ? ($product->name['fa'] ?? $product->name['en'] ?? 'محصول') : $product->name }}</a><p>{{ $product->price }}</p></article>@endforeach</div>
{{ $products->links() }}
</div>
@endsection