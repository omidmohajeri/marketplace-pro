@extends('seller.layout')
@section('title', 'کالاهای من')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold">کالاهای فروشگاه</h1>
    <a href="{{ route('seller.products.create') }}" class="rounded-lg bg-blue-700 px-4 py-2 text-white">ثبت کالای جدید</a>
</div>
<div class="overflow-x-auto rounded-2xl bg-white p-4 shadow-sm">
<table class="w-full text-right text-sm">
    <thead class="border-b text-slate-500"><tr><th class="p-3">نام کالا</th><th class="p-3">شناسه</th><th class="p-3">قیمت</th><th class="p-3">موجودی</th><th class="p-3">وضعیت</th><th class="p-3">عملیات</th></tr></thead>
    <tbody>
    @forelse($products as $product)
        <tr class="border-b last:border-0">
            <td class="p-3">{{ is_array($product->name) ? ($product->name[app()->getLocale()] ?? $product->name['fa'] ?? '') : $product->name }}</td>
            <td class="p-3">{{ $product->sku }}</td><td class="p-3">{{ number_format($product->price) }}</td><td class="p-3">{{ $product->stock }}</td><td class="p-3">{{ $product->status }}</td>
            <td class="p-3"><div class="flex gap-3"><a class="text-blue-700" href="{{ route('seller.products.edit', $product) }}">ویرایش</a>
                <form method="POST" action="{{ route('seller.products.destroy', $product) }}" onsubmit="return confirm('کالا حذف شود؟')">@csrf @method('DELETE')<button class="text-red-700">حذف</button></form></div></td>
        </tr>
    @empty
        <tr><td colspan="6" class="p-5 text-center text-slate-500">کالایی ثبت نکرده‌اید.</td></tr>
    @endforelse
    </tbody>
</table>
<div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
