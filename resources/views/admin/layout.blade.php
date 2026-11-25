<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','مدیریت') | Marketplace Pro</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>body{font-family:Tahoma,Arial,sans-serif;background:#f4f6fa}</style>
</head>
<body class="text-slate-800">
<div class="min-h-screen md:flex">
<aside class="bg-slate-950 text-white md:w-64 p-5">
<a href="{{ route('admin.dashboard') }}" class="text-xl font-black text-white">Marketplace Admin</a>
<nav class="mt-8 space-y-2 text-sm">
<a class="block p-3 rounded-lg hover:bg-slate-800" href="{{ route('admin.dashboard') }}">داشبورد</a>
<a class="block p-3 rounded-lg hover:bg-slate-800" href="{{ route('admin.products.index') }}">محصولات</a>
<a class="block p-3 rounded-lg hover:bg-slate-800" href="{{ route('admin.categories.index') }}">دسته‌بندی‌ها</a>
<a class="block p-3 rounded-lg hover:bg-slate-800" href="{{ route('admin.sellers.index') }}">فروشندگان</a>
<a class="block p-3 rounded-lg hover:bg-slate-800" href="{{ route('admin.orders.index') }}">سفارش‌ها</a>
<a class="block p-3 rounded-lg hover:bg-slate-800" href="{{ route('home') }}">مشاهده فروشگاه</a>
</nav>
</aside>
<div class="flex-1 min-w-0">
<header class="bg-white border-b px-6 py-4 flex justify-between">
<strong>@yield('heading','مدیریت فروشگاه')</strong>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="text-rose-600 text-sm">خروج</button></form>
</header>
<main class="p-5 md:p-8 max-w-7xl mx-auto">
@if(session('success'))<div class="mb-5 rounded-xl bg-emerald-50 text-emerald-800 p-4">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-5 rounded-xl bg-rose-50 text-rose-800 p-4">{{ $errors->first() }}</div>@endif
@yield('content')
</main>
</div>
</div>
</body></html>
