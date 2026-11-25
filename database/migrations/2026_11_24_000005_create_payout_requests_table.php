<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('amount'); // مبلغ درخواستی که سرور از کیف پول کنترل می‌کند
            $table->string('currency', 3)->default('IRR'); // ارز درخواست
            $table->string('status', 30)->default('pending'); // pending, approved, rejected, transferred
            $table->string('bank_account_name')->nullable(); // نام صاحب حساب مقصد
            $table->string('bank_name')->nullable(); // نام بانک مقصد
            $table->string('iban')->nullable(); // شماره شبا مقصد
            $table->text('seller_note')->nullable(); // توضیح فروشنده
            $table->text('admin_note')->nullable(); // توضیح مدیر
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // مدیر بررسی‌کننده
            $table->timestamp('reviewed_at')->nullable(); // زمان بررسی
            $table->timestamp('transferred_at')->nullable(); // زمان ثبت انتقال واقعی
            $table->string('transfer_reference')->nullable(); // شناسه پیگیری انتقال بانکی
            $table->string('debit_idempotency_key')->nullable()->unique(); // کلید یکتای برداشت از کیف پول
            $table->timestamps();

            $table->index(['seller_id', 'status']); // فیلتر درخواست‌های فروشنده
            $table->index(['status', 'created_at']); // صف بررسی مدیر
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
