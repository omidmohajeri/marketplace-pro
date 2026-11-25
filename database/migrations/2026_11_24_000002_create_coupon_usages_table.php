<?php
// ثبت سابقه استفاده از هر کد برای جلوگیری از مصرف بیش از حد
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id(); // شناسه استفاده
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete(); // کوپن مصرف‌شده
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // مشتری استفاده‌کننده
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete(); // سفارش مرتبط پس از ثبت
            $table->unsignedDecimal('discount_amount', 12, 2); // مبلغ واقعی تخفیف
            $table->timestamps();
            $table->unique(['coupon_id', 'order_id']); // جلوگیری از ثبت دوباره برای یک سفارش
            $table->index(['coupon_id', 'user_id']); // شمارش استفاده هر مشتری
        });
    }
    public function down(): void { Schema::dropIfExists('coupon_usages'); }
};