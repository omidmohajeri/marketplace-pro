<?php

namespace App\Services;

use App\Models\PayoutRequest;
use App\Models\Seller;
use App\Models\SellerOrder;
use App\Models\SellerWalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class SellerWalletService
{
    /**
     * اعتباردهی به فروشنده پس از تأیید پرداخت سفارش اصلی.
     * این متد را فقط از مسیر موفقیت PaymentService فراخوانی کنید.
     */
    public function creditPaidSellerOrder(SellerOrder $sellerOrder): ?SellerWalletTransaction
    {
        return DB::transaction(function () use ($sellerOrder) {
            $lockedOrder = SellerOrder::query()->lockForUpdate()->findOrFail($sellerOrder->id);

            // فقط سفارش فروشنده‌ای که واقعاً پرداخت شده است اعتبار می‌گیرد.
            if (!in_array($lockedOrder->status, ['paid', 'payment_confirmed'], true)) {
                return null;
            }

            $seller = Seller::query()->lockForUpdate()->findOrFail($lockedOrder->seller_id);
            $amount = (int) round((float) $lockedOrder->seller_amount);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['amount' => 'مبلغ قابل اعتباردهی معتبر نیست.']);
            }

            $key = 'seller-order-credit:' . $lockedOrder->id;

            // کلید یکتا مانع دوباره‌کاری در callbackهای تکراری می‌شود.
            $existing = SellerWalletTransaction::where('idempotency_key', $key)->first();
            if ($existing) {
                return $existing;
            }

            return SellerWalletTransaction::create([
                'seller_id' => $seller->id,
                'seller_order_id' => $lockedOrder->id,
                'type' => 'net_sale_credit',
                'amount' => $amount,
                'currency' => $lockedOrder->currency ?? 'IRR',
                'idempotency_key' => $key,
                'reference' => (string) $lockedOrder->id,
                'description' => 'اعتبار فروش سفارش فروشنده',
                'meta' => ['source' => 'payment_confirmation'],
            ]);
        }, 3); // تلاش مجدد محدود در صورت بن‌بست دیتابیس
    }

    /**
     * محاسبه مانده قابل برداشت از جمع تراکنش‌های دفترکل.
     * مانده از روی دفترکل محاسبه می‌شود و ستون قابل دستکاری جداگانه ندارد.
     */
    public function balance(int $sellerId, ?string $currency = 'IRR'): int
    {
        $query = SellerWalletTransaction::where('seller_id', $sellerId);
        if ($currency) {
            $query->where('currency', $currency);
        }

        return (int) $query->selectRaw("
            COALESCE(SUM(CASE
                WHEN type IN ('net_sale_credit', 'adjustment_credit') THEN amount
                WHEN type IN ('payout_debit', 'refund_debit', 'adjustment_debit') THEN -amount
                ELSE 0
            END), 0) AS balance
        ")->value('balance');
    }

    /**
     * ایجاد درخواست تسویه؛ مبلغ از ورودی کاربر پذیرفته می‌شود اما مانده توسط سرور کنترل می‌شود.
     */
    public function requestPayout(Seller $seller, array $data): PayoutRequest
    {
        return DB::transaction(function () use ($seller, $data) {
            Seller::query()->lockForUpdate()->findOrFail($seller->id);

            $amount = (int) $data['amount'];
            $currency = $data['currency'] ?? 'IRR';
            if ($amount <= 0 || $amount > $this->balance($seller->id, $currency)) {
                throw ValidationException::withMessages(['amount' => 'مبلغ از مانده قابل برداشت بیشتر است یا معتبر نیست.']);
            }

            // درخواست‌های باز نیز از مانده آزاد کم می‌شوند تا چند درخواست هم‌زمان بیش‌برداشت نسازند.
            $reserved = (int) PayoutRequest::where('seller_id', $seller->id)
                ->where('currency', $currency)
                ->whereIn('status', ['pending', 'approved'])
                ->sum('amount');

            if ($amount + $reserved > $this->balance($seller->id, $currency)) {
                throw ValidationException::withMessages(['amount' => 'با احتساب درخواست‌های باز، مانده کافی نیست.']);
            }

            return PayoutRequest::create([
                'seller_id' => $seller->id,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
                'bank_account_name' => $data['bank_account_name'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'iban' => $data['iban'] ?? null,
                'seller_note' => $data['seller_note'] ?? null,
            ]);
        }, 3);
    }

    /**
     * تأیید اداری درخواست؛ هنوز به معنی انتقال وجه نیست.
     */
    public function approve(PayoutRequest $request, int $adminId, ?string $note = null): PayoutRequest
    {
        return DB::transaction(function () use ($request, $adminId, $note) {
            $locked = PayoutRequest::query()->lockForUpdate()->findOrFail($request->id);
            if ($locked->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'فقط درخواست در انتظار قابل تأیید است.']);
            }

            $locked->update([
                'status' => 'approved',
                'reviewed_by' => $adminId,
                'reviewed_at' => now(),
                'admin_note' => $note,
            ]);

            return $locked->refresh();
        });
    }

    /**
     * ثبت انتقال بانکی واقعی؛ فقط پس از انتقال خارج از سامانه اجرا شود.
     */
    public function markTransferred(PayoutRequest $request, int $adminId, string $reference): SellerWalletTransaction
    {
        return DB::transaction(function () use ($request, $adminId, $reference) {
            $locked = PayoutRequest::query()->lockForUpdate()->findOrFail($request->id);
            if ($locked->status !== 'approved') {
                throw ValidationException::withMessages(['status' => 'فقط درخواست تأییدشده قابل ثبت به‌عنوان انتقال‌یافته است.']);
            }

            $key = 'payout-debit:' . $locked->id;
            $existing = SellerWalletTransaction::where('idempotency_key', $key)->first();
            if ($existing) {
                return $existing;
            }

            $seller = Seller::query()->lockForUpdate()->findOrFail($locked->seller_id);
            $balance = $this->balance($seller->id, $locked->currency);
            $reservedOther = (int) PayoutRequest::where('seller_id', $seller->id)
                ->where('currency', $locked->currency)
                ->where('id', '!=', $locked->id)
                ->whereIn('status', ['pending', 'approved'])
                ->sum('amount');

            if ($locked->amount + $reservedOther > $balance) {
                throw ValidationException::withMessages(['amount' => 'مانده کیف پول برای این تسویه کافی نیست.']);
            }

            $transaction = SellerWalletTransaction::create([
                'seller_id' => $seller->id,
                'payout_request_id' => $locked->id,
                'type' => 'payout_debit',
                'amount' => $locked->amount,
                'currency' => $locked->currency,
                'idempotency_key' => $key,
                'reference' => $reference,
                'description' => 'ثبت انتقال وجه تسویه فروشنده',
                'meta' => ['recorded_by' => $adminId],
            ]);

            $locked->update([
                'status' => 'transferred',
                'reviewed_by' => $adminId,
                'transferred_at' => now(),
                'transfer_reference' => $reference,
                'debit_idempotency_key' => $key,
            ]);

            return $transaction;
        }, 3);
    }

    /**
     * رد درخواست باز؛ مبلغ رزروشده دوباره آزاد می‌شود.
     */
    public function reject(PayoutRequest $request, int $adminId, string $note): PayoutRequest
    {
        return DB::transaction(function () use ($request, $adminId, $note) {
            $locked = PayoutRequest::query()->lockForUpdate()->findOrFail($request->id);
            if (!in_array($locked->status, ['pending', 'approved'], true)) {
                throw ValidationException::withMessages(['status' => 'این درخواست قابل رد نیست.']);
            }

            $locked->update([
                'status' => 'rejected',
                'reviewed_by' => $adminId,
                'reviewed_at' => now(),
                'admin_note' => $note,
            ]);

            return $locked->refresh();
        });
    }
}
