@extends('admin.layout')
@section('title',$category->exists?'ویرایش دسته‌بندی':'دسته‌بندی جدید')
@section('heading',$category->exists?'ویرایش دسته‌بندی':'ایجاد دسته‌بندی')
@section('content')
<form method="POST" action="{{ $category->exists ? route('admin.categories.update',$category) : route('admin.categories.store') }}" class="bg-white border rounded-2xl p-6 grid md:grid-cols-2 gap-5">
@csrf @if($category->exists) @method('PUT') @endif
<label>نام فارسی<input name="name[fa]" required value="{{ old('name.fa',data_get($category->name,'fa')) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>نام انگلیسی<input name="name[en]" required value="{{ old('name.en',data_get($category->name,'en')) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>Slug<input name="slug" value="{{ old('slug',$category->slug) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label>دسته والد<select name="parent_id" class="mt-2 w-full border rounded-xl p-3"><option value="">بدون والد</option>@foreach($parents as $parent)<option value="{{ $parent->id }}" @selected(old('parent_id',$category->parent_id)==$parent->id)>{{ data_get($parent->name,'fa') }}</option>@endforeach</select></label>
<label>ترتیب نمایش<input type="number" min="0" name="sort_order" value="{{ old('sort_order',$category->sort_order ?? 0) }}" class="mt-2 w-full border rounded-xl p-3"></label>
<label class="flex items-center gap-2 mt-8"><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$category->is_active ?? true))> فعال باشد</label>
<label class="md:col-span-2">توضیحات فارسی<textarea name="description[fa]" class="mt-2 w-full border rounded-xl p-3">{{ old('description.fa',data_get($category->description,'fa')) }}</textarea></label>
<label class="md:col-span-2">توضیحات انگلیسی<textarea name="description[en]" class="mt-2 w-full border rounded-xl p-3">{{ old('description.en',data_get($category->description,'en')) }}</textarea></label>
<div class="md:col-span-2"><button class="bg-rose-600 text-white rounded-xl px-6 py-3">ذخیره</button></div>
</form>
@endsection
