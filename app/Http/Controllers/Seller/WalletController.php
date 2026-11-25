<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerWalletTransaction;
use App\Services\SellerWalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request, SellerWalletService $wallet)
    {
        $seller = $request->user()->seller; // فروشنده متصل به کاربر واردشده
        abort_unless($seller, 403); // جلوگیری از دسترسی حساب غیر فروشنده

        $currency = $request->query('currency', 'IRR'); // ارز انتخاب‌شده
        $balance = $wallet->balance($seller->id, $currency); // محاسبه مانده دفترکل
        $transactions = SellerWalletTransaction::where('seller_id', $seller->id)
            ->where('currency', $currency)->latest()->paginate(20); // گردش حساب صفحه‌بندی‌شده

        return view('seller.wallet.index', compact('seller', 'currency', 'balance', 'transactions'));
    }

    public function requestPayout(Request $request, SellerWalletService $wallet)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1'], // مبلغ تسویه
            'currency' => ['required', 'in:IRR'], // فعلاً فقط ریال در این پیاده‌سازی فعال است
            'bank_account_name' => ['nullable', 'string', 'max:150'], // نام صاحب حساب
            'bank_name' => ['nullable', 'string', 'max:100'], // بانک
            'iban' => ['nullable', 'regex:/^IR[0-9]{24}$/'], // اعتبارسنجی ساختار شبا
            'seller_note' => ['nullable', 'string', 'max:1000'], // توضیح فروشنده
        ]);

        $seller = $request->user()->seller; // فروشنده درخواست‌کننده
        abort_unless($seller, 403); // کنترل نقش فروشنده
        $wallet->requestPayout($seller, $data); // ثبت درخواست با کنترل مانده در سرور

        return redirect()->route('seller.wallet.index')->with('success', 'درخواست تسویه ثبت شد.');
    }
}
