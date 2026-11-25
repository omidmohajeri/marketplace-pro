@extends('seller.layout')
@section('title', $product->exists ? 'ویرایش کالا' : 'ثبت کالا')
@section('content')
<h1 class="mb-6 text-2xl font-bold">{{ $product->exists ? 'ویرایش کالا' : 'ثبت کالای جدید' }}</h1>
<form method="POST" action="{{ $product->exists ? route('seller.products.update', $product) : route('seller.products.store') }}" class="grid gap-5 rounded-2xl bg-white p-6 shadow-sm md:grid-cols-2">
    @csrf
    @if($product->exists) @method('PUT') @endif
    {{-- نام فارسی و انگلیسی برای کاتالوگ چندزبانه --}}
    <label class="grid gap-2">نام فارسی<input name="name[fa]" required value="{{ old('name.fa', is_array($product->name) ? ($product->name['fa'] ?? '') : '') }}" class="rounded-lg border p-3"></label>
    <label class="grid gap-2">نام انگلیسی<input name="name[en]" required value="{{ old('name.en', is_array($product->name) ? ($product->name['en'] ?? '') : '') }}" class="rounded-lg border p-3"></label>
    <label class="grid gap-2 md:col-span-2">توضیحات فارسی<textarea name="description[fa]" rows="3" class="rounded-lg border p-3">{{ old('description.fa', is_array($product->description) ? ($product->description['fa'] ?? '') : '') }}</textarea></label>
    <label class="grid gap-2 md:col-span-2">توضیحات انگلیسی<textarea name="description[en]" rows="3" class="rounded-lg border p-3">{{ old('description.en', is_array($product->description) ? ($product->description['en'] ?? '') : '') }}</textarea></label>
    <label class="grid gap-2">دسته‌بندی<select name="category_id" required class="rounded-lg border p-3"><option value="">انتخاب کنید</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ is_array($category->name) ? ($category->name['fa'] ?? '') : $category->name }}</option>@endforeach</select></label>
    <label class="grid gap-2">کد کالا (SKU)<input name="sku" required value="{{ old('sku', $product->sku) }}" class="rounded-lg border p-3"></label>
    <label class="grid gap-2">قیمت<input type="number" min="0" step="0.01" name="price" required value="{{ old('price', $product->price) }}" class="rounded-lg border p-3"></label>
    <label class="grid gap-2">قیمت قبل از تخفیف<input type="number" min="0" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" class="rounded-lg border p-3"></label>
    <label class="grid gap-2">موجودی اولیه<input type="number" min="0" name="stock" required value="{{ old('stock', $product->stock ?? 0) }}" class="rounded-lg border p-3"></label>
    <div class="flex items-end gap-3 md:col-span-2"><button class="rounded-lg bg-blue-700 px-5 py-3 text-white">ذخیره و ارسال برای بررسی</button><a href="{{ route('seller.products.index') }}" class="rounded-lg border px-5 py-3">بازگشت</a></div>
</form>
@endsection
