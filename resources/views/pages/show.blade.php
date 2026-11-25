{{-- نمایش صفحه محتوایی منتشرشده --}}
@extends('layouts.app')
@section('content')
<main class="container" dir="rtl">
    <h1>{{ $page->title }}</h1>
    <article>{!! $page->body !!}</article>
</main>
@endsection
