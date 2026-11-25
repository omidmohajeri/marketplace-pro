<?php
// ثبت گزارش تخلف روی نظر یا پرسش
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('content_reports', function(Blueprint $table) {
   $table->id(); // شناسه گزارش
   $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete(); // گزارش‌دهنده
   $table->string('reportable_type'); // نوع محتوای گزارش‌شده
   $table->unsignedBigInteger('reportable_id'); // شناسه محتوای گزارش‌شده
   $table->string('reason',100); // دلیل گزارش
   $table->text('details')->nullable(); // توضیح تکمیلی
   $table->enum('status',['open','reviewed','dismissed'])->default('open'); // وضعیت رسیدگی
   $table->timestamps();
   $table->index(['reportable_type','reportable_id']); // جستجوی گزارش‌های هر محتوا
  });
 }
 public function down(): void { Schema::dropIfExists('content_reports'); }
};