<?php
// ساخت جدول نظرات و امتیازدهی محصولات
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('product_reviews', function(Blueprint $table) {
   $table->id(); // شناسه نظر
   $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // محصول موردنظر
   $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // نویسنده نظر
   $table->unsignedTinyInteger('rating'); // امتیاز از 1 تا 5
   $table->string('title',180)->nullable(); // عنوان نظر
   $table->text('body'); // متن نظر
   $table->enum('status',['pending','approved','rejected'])->default('pending'); // وضعیت بررسی مدیر
   $table->boolean('verified_purchase')->default(false); // خرید تأییدشده؛ فقط سرور تعیین کند
   $table->timestamps();
   $table->index(['product_id','status']); // بهینه‌سازی نمایش نظرات تأییدشده
   $table->unique(['product_id','user_id']); // هر کاربر برای هر محصول یک نظر فعال
  });
 }
 public function down(): void { Schema::dropIfExists('product_reviews'); }
};