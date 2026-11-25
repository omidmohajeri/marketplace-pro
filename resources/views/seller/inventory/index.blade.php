@extends('seller.layout')
@section('title', 'موجودی انبار')
@section('content')
<h1 class="mb-6 text-2xl font-bold">مدیریت موجودی</h1>
<div class="grid gap-4">
@forelse($products as $product)
    <form method="POST" action="{{ route('seller.inventory.update', $product) }}" class="flex flex-wrap items-center justify-between gap-4 rounded-xl bg-white p-4 shadow-sm">
        @csrf @method('PATCH')
        <div><p class="font-semibold">{{ is_array($product->name) ? ($product->name['fa'] ?? '') : $product->name }}</p><p class="text-sm text-slate-500">SKU: {{ $product->sku }} | وضعیت: {{ $product->status }}</p></div>
        <div class="flex items-center gap-2"><label for="stock-{{ $product->id }}">موجودی</label><input id="stock-{{ $product->id }}" type="number" min="0" name="stock" value="{{ $product->stock }}" class="w-28 rounded-lg border p-2"><button class="rounded-lg bg-slate-900 px-4 py-2 text-white">ثبت</button></div>
    </form>
@empty
    <div class="rounded-xl bg-white p-6 text-center text-slate-500">کالایی برای مدیریت موجودی وجود ندارد.</div>
@endforelse
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
