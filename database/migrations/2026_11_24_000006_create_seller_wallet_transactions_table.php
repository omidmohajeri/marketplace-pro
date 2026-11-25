<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seller_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payout_request_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 40); // نوع تراکنش: net_sale_credit, payout_debit, refund_debit, adjustment_credit/debit
            $table->bigInteger('amount'); // مبلغ به کوچک‌ترین واحد پولی پروژه؛ عدد مثبت ذخیره می‌شود
            $table->string('currency', 3)->default('IRR'); // کد ارز تراکنش
            $table->string('idempotency_key')->unique(); // جلوگیری از ثبت دوباره یک رویداد
            $table->string('reference')->nullable(); // شناسه مرجع بانکی یا سفارش
            $table->text('description')->nullable(); // توضیح قابل مشاهده در سوابق
            $table->json('meta')->nullable(); // اطلاعات تکمیلی و ممیزی
            $table->timestamps();

            $table->index(['seller_id', 'created_at']); // سرعت نمایش گردش کیف پول
            $table->index(['seller_id', 'currency']); // فیلتر تراکنش بر اساس ارز
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_wallet_transactions');
    }
};
