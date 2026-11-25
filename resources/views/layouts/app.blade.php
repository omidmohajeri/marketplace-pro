<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', __('فروشگاه آنلاین')) | Marketplace Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body{font-family:Tahoma,Arial,sans-serif;background:#f6f7fb}
      .container{max-width:1200px}
      [dir=ltr] body{text-align:left}
    </style>
</head>
<body class="text-slate-800">
<header class="bg-white border-b sticky top-0 z-30">
  <div class="container mx-auto px-4 py-4 flex items-center gap-5">
    <a href="{{ route('home') }}" class="text-2xl font-black text-rose-600">Market<span class="text-slate-800">place</span></a>
    <form action="{{ route('products.index') }}" class="flex-1 flex">
      <input name="q" value="{{ request('q') }}" placeholder="{{ __('جستجو') }}..." class="w-full bg-slate-100 rounded-r-xl px-4 py-3 outline-none">
      <button class="bg-slate-900 text-white px-5 rounded-l-xl">{{ __('جستجو') }}</button>
    </form>
    <a class="text-sm font-bold" href="{{ route('locale.switch', app()->getLocale()==='fa'?'en':'fa') }}">{{ app()->getLocale()==='fa'?'English':'فارسی' }}</a>
  </div>
  <nav class="container mx-auto px-4 pb-3 text-sm text-slate-500 flex gap-6">
    <a href="{{ route('home') }}">{{ __('خانه') }}</a><a href="{{ route('products.index') }}">{{ __('فروشگاه') }}</a>
  </nav>
</header>
<main class="container mx-auto px-4 py-8">@yield('content')</main>
<footer class="mt-16 bg-slate-900 text-slate-300 py-10"><div class="container mx-auto px-4">Marketplace Pro · فروشگاه چندفروشندگی</div></footer>
</body></html>
