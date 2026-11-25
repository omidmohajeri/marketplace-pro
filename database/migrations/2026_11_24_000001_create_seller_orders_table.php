<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// این مهاجرت سفارش‌های تفکیک‌شده هر فروشنده را در کنار سفارش اصلی نگه می‌دارد.
return new class extends Migration {
    public function up(): void
    {
        // هر فروشنده برای هر سفارش اصلی حداکثر یک سفارش فرعی دارد.
        Schema::create('seller_orders', function (Blueprint $table) {
            $table->id(); // شناسه یکتای سفارش فروشنده
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); // سفارش اصلی خریدار
            $table->foreignId('seller_id')->constrained()->cascadeOnDelete(); // فروشگاه دریافت‌کننده سفارش
            $table->string('number', 40)->unique(); // شماره قابل نمایش سفارش فروشنده
            $table->decimal('subtotal', 14, 2)->default(0); // جمع مبلغ کالاهای این فروشنده
            $table->decimal('commission_rate', 7, 4)->default(0); // درصد کمیسیون ذخیره‌شده در لحظه سفارش
            $table->decimal('commission_amount', 14, 2)->default(0); // مبلغ کمیسیون پلتفرم
            $table->decimal('seller_amount', 14, 2)->default(0); // مبلغ خالص متعلق به فروشنده
            $table->string('status', 30)->default('pending_payment'); // وضعیت مستقل سفارش فروشنده
            $table->timestamps(); // زمان ایجاد و آخرین تغییر
            $table->unique(['order_id', 'seller_id']); // جلوگیری از ساخت سفارش تکراری برای یک فروشنده
            $table->index(['seller_id', 'status']); // سرعت گزارش‌گیری فروشنده
        });
    }

    public function down(): void
    {
        // جدول وابسته پیش از جدول اصلی حذف می‌شود.
        Schema::dropIfExists('seller_orders');
    }
};
