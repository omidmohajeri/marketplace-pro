<?php
// جدول پرسش‌های مشتری و پاسخ‌های فروشنده/مدیر
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('product_questions', function(Blueprint $table) {
   $table->id(); // شناسه پرسش
   $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // محصول مرتبط
   $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // پرسشگر
   $table->text('question'); // متن پرسش
   $table->text('answer')->nullable(); // پاسخ
   $table->foreignId('answered_by')->nullable()->constrained('users')->nullOnDelete(); // پاسخ‌دهنده
   $table->enum('status',['pending','answered','rejected'])->default('pending'); // وضعیت نمایش
   $table->timestamps();
   $table->index(['product_id','status']); // فهرست پرسش‌های قابل نمایش
  });
 }
 public function down(): void { Schema::dropIfExists('product_questions'); }
};