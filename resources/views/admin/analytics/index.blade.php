{{-- داشبورد خلاصه فروش --}}
@extends('admin.layout')
@section('content')
<div dir="rtl"><h1>گزارش فروش</h1>
<form method="GET"><input type="date" name="from" value="{{ $range['from'] ?? '' }}"><input type="date" name="to" value="{{ $range['to'] ?? '' }}"><button>اعمال</button></form>
<p>تعداد سفارش: {{ $summary->orders_count ?? 0 }}</p><p>فروش ناخالص: {{ number_format($summary->gross_sales ?? 0) }}</p>
<table><tr><th>روز</th><th>سفارش</th><th>فروش</th></tr>@foreach($daily as $row)<tr><td>{{ $row->day }}</td><td>{{ $row->orders_count }}</td><td>{{ number_format($row->gross_sales) }}</td></tr>@endforeach</table></div>
@endsection