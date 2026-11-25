<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// این مهاجرت تلاش‌های پرداخت را مستقل از سفارش اصلی ذخیره می‌کند.
return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_attempts', function (Blueprint $table) {
            $table->id(); // شناسه تلاش پرداخت
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); // سفارش هدف پرداخت
            $table->string('gateway', 60); // نام درگاه پرداخت انتخاب‌شده
            $table->string('status', 30)->default('initiated'); // وضعیت تلاش پرداخت
            $table->decimal('amount', 14, 2); // مبلغ مورد انتظار از درگاه
            $table->string('currency', 8)->default('IRR'); // واحد پول مبلغ
            $table->string('authority', 150)->nullable()->index(); // شناسه پرداخت صادرشده توسط درگاه
            $table->string('reference', 150)->nullable()->index(); // کد پیگیری نهایی درگاه
            $table->json('gateway_payload')->nullable(); // پاسخ پاک‌سازی‌شده درگاه برای عیب‌یابی
            $table->timestamp('paid_at')->nullable(); // زمان تأیید قطعی پرداخت
            $table->timestamps(); // زمان ایجاد و به‌روزرسانی
            $table->index(['order_id', 'status']); // سرعت یافتن پرداخت‌های سفارش
        });
    }

    public function down(): void
    {
        // جدول تلاش‌های پرداخت حذف می‌شود.
        Schema::dropIfExists('payment_attempts');
    }
};
