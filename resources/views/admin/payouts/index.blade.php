@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-7xl p-4" dir="rtl">
    <h1 class="mb-5 text-2xl font-bold">مدیریت تسویه فروشندگان</h1>
    @if(session('success')) <div class="mb-4 rounded bg-green-100 p-3">{{ session('success') }}</div> @endif

    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 text-right"><th class="p-3">فروشنده</th><th class="p-3">مبلغ</th><th class="p-3">حساب مقصد</th><th class="p-3">وضعیت</th><th class="p-3">عملیات</th></tr></thead>
            <tbody>
            @forelse($requests as $payout)
                <tr class="border-t align-top">
                    <td class="p-3">{{ $payout->seller->id }}</td>
                    <td class="p-3">{{ number_format($payout->amount) }} {{ $payout->currency }}</td>
                    <td class="p-3">{{ $payout->bank_name }}<br>{{ $payout->bank_account_name }}<br><span dir="ltr">{{ $payout->iban }}</span></td>
                    <td class="p-3">{{ $payout->status }}</td>
                    <td class="min-w-64 space-y-2 p-3">
                        @if($payout->status === 'pending')
                            <form method="POST" action="{{ route('admin.payouts.approve', $payout) }}">@csrf
                                <input name="admin_note" placeholder="یادداشت مدیر" class="mb-1 w-full rounded border p-2">
                                <button class="rounded bg-blue-700 px-3 py-2 text-white">تأیید درخواست</button>
                            </form>
                            <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}">@csrf
                                <input name="admin_note" required placeholder="دلیل رد" class="mb-1 w-full rounded border p-2">
                                <button class="rounded bg-red-700 px-3 py-2 text-white">رد درخواست</button>
                            </form>
                        @elseif($payout->status === 'approved')
                            <form method="POST" action="{{ route('admin.payouts.transferred', $payout) }}">@csrf
                                <input name="transfer_reference" required placeholder="شناسه پیگیری بانکی" class="mb-1 w-full rounded border p-2">
                                <button class="rounded bg-green-700 px-3 py-2 text-white">ثبت انتقال واقعی</button>
                            </form>
                            <p class="text-xs text-amber-700">فقط پس از انجام انتقال بانکی از این گزینه استفاده کنید.</p>
                        @else
                            {{ $payout->transfer_reference ?? $payout->admin_note ?? '—' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-5 text-center">درخواستی ثبت نشده است.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $requests->links() }}</div>
</div>
@endsection
