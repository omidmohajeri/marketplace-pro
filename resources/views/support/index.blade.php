{{-- مرکز پشتیبانی مشتری --}}
@extends('layouts.app')
@section('content')
<div class="container" dir="rtl">
    <h1>درخواست‌های پشتیبانی</h1>
    <form method="POST" action="{{ route('support.store') }}">
        @csrf
        <input name="subject" required maxlength="180" placeholder="موضوع تیکت">
        <select name="category"><option value="general">عمومی</option><option value="order">سفارش</option><option value="payment">پرداخت</option><option value="shipping">ارسال</option></select>
        <textarea name="body" required minlength="5" maxlength="5000" placeholder="شرح درخواست"></textarea>
        <button type="submit">ثبت تیکت</button>
    </form>
    @foreach($tickets as $ticket)
        <article><b>#{{ $ticket->id }} — {{ $ticket->subject }}</b><p>{{ $ticket->status }}</p></article>
    @endforeach
    {{ $tickets->links() }}
</div>
@endsection
