@extends('admin.layout')
@section('title','جزئیات سفارش')
@section('heading','جزئیات سفارش '.$order->number)
@section('content')
<div class="grid md:grid-cols-2 gap-5">
<section class="bg-white border rounded-2xl p-5"><h2 class="font-black mb-3">اطلاعات مشتری</h2><p>{{ $order->user?->name }}</p><p>{{ $order->user?->email }}</p><pre class="whitespace-pre-wrap text-sm mt-4">{{ json_encode($order->shipping_address,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre></section>
<section class="bg-white border rounded-2xl p-5"><h2 class="font-black mb-3">وضعیت سفارش</h2><p class="mb-4">وضعیت فعلی: {{ $order->status }}</p>
<form method="POST" action="{{ route('admin.orders.status',$order) }}">@csrf @method('PATCH')<select name="status" class="w-full border rounded-xl p-3 mb-3">@foreach(['pending','paid','processing','shipped','delivered','cancelled','refunded'] as $s)<option value="{{ $s }}" @selected($order->status===$s)>{{ $s }}</option>@endforeach</select><button class="bg-rose-600 text-white rounded-xl px-5 py-3">ثبت وضعیت</button></form>
<p class="text-xs text-amber-700 mt-3">تغییر وضعیت به «پرداخت‌شده» در این پنل جایگزین تأیید درگاه بانکی نیست.</p></section>
</div>
<section class="bg-white border rounded-2xl p-5 mt-5"><h2 class="font-black mb-4">اقلام سفارش</h2>
<div class="overflow-x-auto"><table class="w-full text-sm text-right"><thead><tr><th class="p-2">کالا</th><th class="p-2">SKU</th><th class="p-2">تعداد</th><th class="p-2">قیمت واحد</th><th class="p-2">جمع</th></tr></thead><tbody>
@foreach($order->items as $item)<tr class="border-t"><td class="p-2">{{ data_get($item->product_name,'fa',data_get($item->product_name,'en')) }}</td><td class="p-2">{{ $item->sku }}</td><td class="p-2">{{ $item->quantity }}</td><td class="p-2">{{ number_format($item->unit_price) }}</td><td class="p-2">{{ number_format($item->line_total) }}</td></tr>@endforeach
</tbody></table></div><p class="font-black mt-5">مبلغ نهایی: {{ number_format($order->grand_total) }} {{ $order->currency }}</p></section>
@endsection
