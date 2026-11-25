<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'fa' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'پنل فروشنده') | Marketplace Pro</title>
    {{-- استایل پایه؛ برای محیط عملیاتی بهتر است Tailwind از طریق Vite build شود. --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="min-h-screen md:flex">
        <aside class="w-full bg-slate-900 p-5 text-white md:min-h-screen md:w-64">
            <a href="{{ route('seller.dashboard') }}" class="text-xl font-bold">پنل فروشنده</a>
            <nav class="mt-8 grid gap-2 text-sm">
                <a class="rounded-lg p-3 hover:bg-slate-800" href="{{ route('seller.dashboard') }}">داشبورد</a>
                <a class="rounded-lg p-3 hover:bg-slate-800" href="{{ route('seller.products.index') }}">کالاهای من</a>
                <a class="rounded-lg p-3 hover:bg-slate-800" href="{{ route('seller.products.create') }}">ثبت کالای جدید</a>
                <a class="rounded-lg p-3 hover:bg-slate-800" href="{{ route('seller.inventory.index') }}">موجودی انبار</a>
                <a class="rounded-lg p-3 hover:bg-slate-800" href="{{ route('seller.orders.index') }}">سفارش‌ها</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-5">
                    @csrf
                    <button class="w-full rounded-lg bg-slate-800 p-3 text-right hover:bg-slate-700">خروج</button>
                </form>
            </nav>
        </aside>
        <main class="min-w-0 flex-1 p-5 md:p-8">
            @if(session('success'))
                <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-3 text-red-800">
                    <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
