@extends('admin.layout')
@section('title','دسته‌بندی‌ها')
@section('heading','مدیریت دسته‌بندی‌ها')
@section('content')
<div class="mb-5"><a href="{{ route('admin.categories.create') }}" class="inline-block bg-rose-600 text-white rounded-xl px-4 py-2">دسته‌بندی جدید</a></div>
<div class="bg-white border rounded-2xl overflow-x-auto"><table class="w-full text-sm text-right">
<thead class="bg-slate-50"><tr><th class="p-3">نام فارسی</th><th class="p-3">نام انگلیسی</th><th class="p-3">تعداد کالا</th><th class="p-3">وضعیت</th><th class="p-3">عملیات</th></tr></thead>
<tbody>@forelse($categories as $category)<tr class="border-t"><td class="p-3">{{ data_get($category->name,'fa') }}</td><td class="p-3">{{ data_get($category->name,'en') }}</td><td class="p-3">{{ $category->products_count }}</td><td class="p-3">{{ $category->is_active?'فعال':'غیرفعال' }}</td><td class="p-3 flex gap-3"><a class="text-blue-600" href="{{ route('admin.categories.edit',$category) }}">ویرایش</a><form method="POST" action="{{ route('admin.categories.destroy',$category) }}" onsubmit="return confirm('حذف شود؟')">@csrf @method('DELETE')<button class="text-rose-600">حذف</button></form></td></tr>@empty<tr><td colspan="5" class="p-4">دسته‌بندی ثبت نشده است.</td></tr>@endforelse</tbody></table></div>
<div class="mt-5">{{ $categories->links() }}</div>
@endsection
