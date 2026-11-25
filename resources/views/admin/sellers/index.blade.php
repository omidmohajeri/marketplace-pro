@extends('admin.layout')
@section('title','فروشندگان')
@section('heading','مدیریت فروشندگان')
@section('content')
<form class="mb-5 flex gap-2"><select name="status" class="border rounded-xl p-2"><option value="">همه وضعیت‌ها</option>@foreach(['pending','active','suspended'] as $status)<option value="{{ $status }}" @selected(request('status')===$status)>{{ $status }}</option>@endforeach</select><button class="bg-slate-900 text-white rounded-xl px-4">فیلتر</button></form>
<div class="bg-white border rounded-2xl overflow-x-auto"><table class="w-full text-sm text-right">
<thead class="bg-slate-50"><tr><th class="p-3">فروشگاه</th><th class="p-3">مالک</th><th class="p-3">وضعیت و کمیسیون</th><th class="p-3">ذخیره</th></tr></thead>
<tbody>@forelse($sellers as $seller)<tr class="border-t"><td class="p-3 font-bold">{{ $seller->store_name }}</td><td class="p-3">{{ $seller->user?->name }}<br>{{ $seller->user?->email }}</td><td class="p-3"><form id="seller-{{ $seller->id }}" method="POST" action="{{ route('admin.sellers.update',$seller) }}">@csrf @method('PATCH')<select name="status" class="border rounded-lg p-2">@foreach(['pending','active','suspended'] as $s)<option value="{{ $s }}" @selected($seller->status===$s)>{{ $s }}</option>@endforeach</select><input name="commission_rate" type="number" min="0" max="100" step="0.01" value="{{ $seller->commission_rate }}" class="w-24 border rounded-lg p-2"></form></td><td class="p-3"><button form="seller-{{ $seller->id }}" class="text-rose-600 font-bold">ذخیره</button></td></tr>@empty<tr><td colspan="4" class="p-4">فروشنده‌ای ثبت نشده است.</td></tr>@endforelse</tbody></table></div>
<div class="mt-5">{{ $sellers->links() }}</div>
@endsection
