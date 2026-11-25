@extends('admin.layout')
@section('title','محصولات')
@section('heading','مدیریت محصولات')
@section('content')
<div class="flex flex-wrap justify-between gap-3 mb-5">
<form class="flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="جست‌وجوی نام یا SKU" class="border rounded-xl px-4 py-2"><button class="bg-slate-900 text-white rounded-xl px-4">جست‌وجو</button></form>
<a href="{{ route('admin.products.create') }}" class="bg-rose-600 text-white rounded-xl px-4 py-2">افزودن محصول</a>
</div>
<div class="bg-white border rounded-2xl overflow-x-auto"><table class="w-full text-sm text-right">
<thead class="bg-slate-50"><tr><th class="p-3">محصول</th><th class="p-3">SKU</th><th class="p-3">قیمت</th><th class="p-3">موجودی</th><th class="p-3">وضعیت</th><th class="p-3">عملیات</th></tr></thead>
<tbody>@forelse($products as $product)<tr class="border-t"><td class="p-3 font-bold">{{ data_get($product->name,'fa') }}</td><td class="p-3">{{ $product->sku }}</td><td class="p-3">{{ number_format($product->price) }}</td><td class="p-3">{{ $product->stock }}</td><td class="p-3">{{ $product->status }}</td><td class="p-3 flex gap-3"><a class="text-blue-600" href="{{ route('admin.products.edit',$product) }}">ویرایش</a><form method="POST" action="{{ route('admin.products.destroy',$product) }}" onsubmit="return confirm('محصول حذف شود؟')">@csrf @method('DELETE')<button class="text-rose-600">حذف</button></form></td></tr>@empty<tr><td colspan="6" class="p-4">محصولی ثبت نشده است.</td></tr>@endforelse</tbody></table></div>
<div class="mt-5">{{ $products->links() }}</div>
@endsection
