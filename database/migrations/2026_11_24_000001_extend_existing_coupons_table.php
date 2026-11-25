<?php
// توسعه جدول coupons فاز اول برای پشتیبانی از امکانات کمپین فاز نهم
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('name')->default('')->after('code'); // عنوان کمپین
            $table->text('description')->nullable()->after('name'); // توضیحات
            $table->decimal('minimum_order_amount', 14, 2)->default(0)->after('value'); // حداقل مبلغ سفارش
            $table->decimal('maximum_discount_amount', 14, 2)->nullable()->after('minimum_order_amount'); // سقف تخفیف
            $table->unsignedInteger('usage_limit_per_user')->nullable()->after('usage_limit'); // محدودیت هر کاربر
        });
        // در اسکیمای فاز اول مقدار درصد با نام percent ذخیره می‌شود؛ کد سرویس آن را پشتیبانی می‌کند.
    }
    public function down(): void {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['name','description','minimum_order_amount','maximum_discount_amount','usage_limit_per_user']);
        });
    }
};
