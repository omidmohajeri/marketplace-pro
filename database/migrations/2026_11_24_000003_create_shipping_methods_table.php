<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام روش ارسال مانند پست یا پیک
            $table->string('code')->unique(); // کد داخلی یکتای روش ارسال
            $table->text('description')->nullable(); // توضیح روش ارسال
            $table->bigInteger('base_cost')->default(0); // هزینه پایه به واحد پول اصلی
            $table->bigInteger('cost_per_kg')->default(0); // هزینه اضافه به ازای هر کیلوگرم
            $table->bigInteger('free_shipping_threshold')->nullable(); // حداقل مبلغ برای ارسال رایگان
            $table->unsignedInteger('estimated_min_days')->default(1); // حداقل زمان تخمینی تحویل
            $table->unsignedInteger('estimated_max_days')->default(5); // حداکثر زمان تخمینی تحویل
            $table->boolean('is_active')->default(true); // فعال بودن روش ارسال
            $table->json('meta')->nullable(); // تنظیمات تکمیلی روش ارسال
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
