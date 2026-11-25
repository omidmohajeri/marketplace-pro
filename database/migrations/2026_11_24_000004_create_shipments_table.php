<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_order_id')->unique()->constrained()->cascadeOnDelete(); // هر سفارش فروشنده یک مرسوله اصلی دارد
            $table->foreignId('shipping_method_id')->nullable()->constrained()->nullOnDelete(); // روش انتخاب‌شده ارسال
            $table->string('status', 30)->default('pending'); // pending, preparing, shipped, in_transit, delivered, failed, returned
            $table->string('carrier_name')->nullable(); // نام شرکت حمل‌کننده
            $table->string('tracking_number')->nullable()->index(); // کد رهگیری مرسوله
            $table->string('tracking_url')->nullable(); // لینک رهگیری رسمی
            $table->timestamp('shipped_at')->nullable(); // زمان تحویل مرسوله به حمل‌کننده
            $table->timestamp('delivered_at')->nullable(); // زمان تحویل نهایی
            $table->text('seller_note')->nullable(); // توضیح فروشنده
            $table->json('shipping_address_snapshot')->nullable(); // نشانی مقصد در لحظه ثبت مرسوله
            $table->bigInteger('shipping_cost')->default(0); // هزینه ارسال محاسبه‌شده
            $table->string('currency', 3)->default('IRR'); // ارز هزینه ارسال
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
