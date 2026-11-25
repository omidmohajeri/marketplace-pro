<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Services\SellerWalletService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        $requests = PayoutRequest::with('seller')->latest()->paginate(30); // صف درخواست‌های تسویه
        return view('admin.payouts.index', compact('requests'));
    }

    public function approve(Request $request, PayoutRequest $payout, SellerWalletService $wallet)
    {
        $data = $request->validate(['admin_note' => ['nullable', 'string', 'max:1000']]); // توضیح مدیر
        $wallet->approve($payout, (int) $request->user()->id, $data['admin_note'] ?? null); // تأیید بدون ادعای انتقال وجه
        return back()->with('success', 'درخواست تأیید شد؛ انتقال بانکی هنوز ثبت نشده است.');
    }

    public function reject(Request $request, PayoutRequest $payout, SellerWalletService $wallet)
    {
        $data = $request->validate(['admin_note' => ['required', 'string', 'max:1000']]); // دلیل رد اجباری
        $wallet->reject($payout, (int) $request->user()->id, $data['admin_note']); // رد و آزادسازی مبلغ رزروشده
        return back()->with('success', 'درخواست رد شد.');
    }

    public function transferred(Request $request, PayoutRequest $payout, SellerWalletService $wallet)
    {
        $data = $request->validate(['transfer_reference' => ['required', 'string', 'max:150']]); // شناسه پیگیری واقعی بانک
        $wallet->markTransferred($payout, (int) $request->user()->id, $data['transfer_reference']); // ثبت برداشت در دفترکل
        return back()->with('success', 'انتقال بانکی در سیستم ثبت شد.');
    }
}
