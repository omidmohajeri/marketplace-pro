{{-- این partial را داخل head قالب اصلی قرار دهید --}}
<title>{{ $seoTitle ?? config('app.name') }}</title>
<meta name="description" content="{{ $seoDescription ?? '' }}">
<link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
<meta property="og:title" content="{{ $seoTitle ?? config('app.name') }}">
<meta property="og:description" content="{{ $seoDescription ?? '' }}">
<meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">