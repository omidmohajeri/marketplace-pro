{{-- مرکز اعلان‌های مشتری --}}
@extends('layouts.app')
@section('content')
<div dir="rtl"><h1>اعلان‌های من</h1><form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button>خواندن همه</button></form>
@foreach($notifications as $n)<article><b>{{ $n->data['title'] ?? 'اعلان' }}</b><p>{{ $n->data['message'] ?? '' }}</p>
@if(!$n->read_at)<form method="POST" action="{{ route('notifications.read',$n->id) }}">@csrf @method('PATCH')<button>خوانده شد</button></form>@endif</article>@endforeach
{{ $notifications->links() }}</div>
@endsection