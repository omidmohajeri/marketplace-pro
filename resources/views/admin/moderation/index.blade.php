{{-- داشبورد پایه نظارت بر محتوای کاربران --}}
@extends('admin.layout')
@section('content')
<div class="container" dir="rtl">
<h1>مدیریت نظرات و پرسش‌ها</h1>
@if(session('success'))<p>{{ session('success') }}</p>@endif
<h2>نظرات در انتظار تأیید</h2>
@foreach($reviews as $review)
<article><b>{{ $review->product->name ?? 'محصول' }}</b> — {{ $review->user->name ?? 'کاربر' }}
<p>{{ $review->body }}</p><p>امتیاز: {{ $review->rating }} / 5</p>
<form method="POST" action="{{ route('admin.moderation.reviews.update',$review) }}">@csrf @method('PATCH')
<button name="status" value="approved">تأیید</button><button name="status" value="rejected">رد</button></form></article>
@endforeach
{{ $reviews->links() }}
<h2>پرسش‌های در انتظار بررسی</h2>
@foreach($questions as $question)
<article><b>{{ $question->product->name ?? 'محصول' }}</b><p>{{ $question->question }}</p>
<form method="POST" action="{{ route('admin.moderation.questions.update',$question) }}">@csrf @method('PATCH')
<button name="status" value="approved">تأیید</button><button name="status" value="rejected">رد</button></form></article>
@endforeach
{{ $questions->links() }}
<h2>گزارش‌های باز</h2>
@foreach($reports as $report)
<article><p>{{ $report->reason }} — {{ $report->details }}</p>
<form method="POST" action="{{ route('admin.moderation.reports.update',$report) }}">@csrf @method('PATCH')
<button name="status" value="reviewed">رسیدگی شد</button><button name="status" value="dismissed">رد گزارش</button></form></article>
@endforeach
{{ $reports->links() }}
</div>
@endsection