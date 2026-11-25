@extends('admin.layout')
@section('title',$product->exists?'ویرایش محصول':'محصول جدید')
@section('heading',$product->exists?'ویرایش محصول':'افزودن محصول')
@section('content')
<form method="POST" action="{{ $product->exists ? route('admin.products.update',$product) : route('admin.products.store') }}" class="bg-white border rounded-2xl p-6 grid md:grid-cols-2 gap-5">
@csrf @if($product->exists) @method('PUT') @endif
<label>نام فارسی<input name="name[fa]" required value="{{ old('name.fa',data_get($product->name,'fa')) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>نام انگلیسی<input name="name[en]" required value="{{ old('name.en',data_get($product->name,'en')) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>کد کالا (SKU)<input name="sku" required value="{{ old('sku',$product->sku) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>شناسه URL (اختیاری)<input name="slug" value="{{ old('slug',$product->slug) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>دسته‌بندی<select name="category_id" required class="mt-2 w-full border rounded-xl p-3">@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id',$product->category_id)==$category->id)>{{ data_get($category->name,'fa') }}</option>@endforeach</select></label>
<label>قیمت (واحد پول دیتابیس)<input type="number" min="0" name="price" required value="{{ old('price',$product->price) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>قیمت قبل از تخفیف<input type="number" min="0" name="compare_at_price" value="{{ old('compare_at_price',$product->compare_at_price) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>موجودی<input type="number" min="0" name="stock" required value="{{ old('stock',$product->stock ?? 0) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>وضعیت<select name="status" class="mt-2 w-full border rounded-xl p-3">@foreach(['draft'=>'پیش‌نویس','active'=>'فعال','archived'=>'بایگانی'] as $key=>$label)<option value="{{ $key }}" @selected(old('status',$product->status ?? 'draft')===$key)>{{ $label }}</option>@endforeach</select></label>
<label class="md:col-span-2">توضیحات فارسی<textarea name="description[fa]" class="mt-2 w-full border rounded-xl p-3">{{ old('description.fa',data_get($product->description,'fa')) }}</textarea></label>
<label class="md:col-span-2">توضیحات انگلیسی<textarea name="description[en]" class="mt-2 w-full border rounded-xl p-3">{{ old('description.en',data_get($product->description,'en')) }}</textarea></label>
<div class="md:col-span-2 flex gap-3"><button class="bg-rose-600 text-white rounded-xl px-6 py-3">ذخیره محصول</button><a href="{{ route('admin.products.index') }}" class="border rounded-xl px-6 py-3">بازگشت</a></div>
</form>
@endsection
