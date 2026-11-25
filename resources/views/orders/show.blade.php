{{-- جزئیات پایه سفارش مشتری --}}
@extends('layouts.app')
@section('content')
<div class="container" dir="rtl">
    <h1>سفارش {{ $order->number }}</h1>
    <p>وضعیت: {{ $order->status }}</p>
    <p>مبلغ نهایی: {{ number_format($order->grand_total) }} {{ $order->currency }}</p>
    @foreach($order->items as $item)
        <article><b>{{ is_array($item->product_name) ? ($item->product_name['fa'] ?? $item->product_name['en'] ?? 'محصول') : $item->product_name }}</b> — {{ $item->quantity }} عدد</article>
    @endforeach
    @if(in_array($order->status,['pending','pending_payment','unpaid'],true))
        <form method="POST" action="{{ route('payments.checkout',$order) }}">@csrf<button type="submit">پرداخت سفارش</button></form>
    @endif
</div>
@endsection
