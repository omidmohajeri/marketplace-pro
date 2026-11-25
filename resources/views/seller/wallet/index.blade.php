@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-6xl p-4" dir="rtl">
    <h1 class="mb-5 text-2xl font-bold">کیف پول فروشنده</h1>

    @if(session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="mb-6 rounded-xl border bg-white p-5 shadow-sm">
        <div class="text-sm text-gray-500">مانده دفترکل ({{ $currency }})</div>
        <div class="mt-2 text-3xl font-bold">{{ number_format($balance) }}</div>
        <p class="mt-2 text-xs text-gray-500">مانده از تراکنش‌های ثبت‌شده محاسبه می‌شود؛ درخواست‌های باز هنگام ثبت درخواست کنترل می‌شوند.</p>
    </div>

    <form method="POST" action="{{ route('seller.wallet.payout') }}" class="mb-8 grid gap-3 rounded-xl border bg-white p-5 md:grid-cols-2">
        @csrf
        <h2 class="md:col-span-2 text-lg font-bold">درخواست تسویه</h2>
        <input name="amount" type="number" min="1" required placeholder="مبلغ به ریال" class="rounded border p-3">
        <input name="currency" type="hidden" value="IRR">
        <input name="bank_account_name" placeholder="نام صاحب حساب" class="rounded border p-3">
        <input name="bank_name" placeholder="نام بانک" class="rounded border p-3">
        <input name="iban" placeholder="شماره شبا با IR" class="rounded border p-3" dir="ltr">
        <input name="seller_note" placeholder="توضیحات (اختیاری)" class="rounded border p-3">
        <button class="rounded bg-slate-900 p-3 text-white md:col-span-2">ثبت درخواست تسویه</button>
    </form>

    <h2 class="mb-3 text-lg font-bold">گردش کیف پول</h2>
    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-right"><th class="p-3">تاریخ</th><th class="p-3">نوع</th><th class="p-3">مبلغ</th><th class="p-3">مرجع</th><th class="p-3">توضیح</th></tr></thead>
            <tbody>
            @forelse($transactions as $tx)
                <tr class="border-t">
                    <td class="p-3">{{ $tx->created_at->format('Y/m/d H:i') }}</td>
                    <td class="p-3">{{ $tx->type }}</td>
                    <td class="p-3">{{ number_format($tx->amount) }} {{ $tx->currency }}</td>
                    <td class="p-3">{{ $tx->reference ?? '—' }}</td>
                    <td class="p-3">{{ $tx->description ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-5 text-center text-gray-500">هنوز تراکنشی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
@endsection
