<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete(); // مرسوله مرتبط با رویداد
            $table->string('status', 30); // وضعیت ثبت‌شده در این رویداد
            $table->string('title'); // عنوان قابل نمایش برای مشتری
            $table->text('description')->nullable(); // شرح رویداد
            $table->string('location')->nullable(); // موقعیت ثبت‌شده توسط حمل‌کننده
            $table->timestamp('occurred_at')->nullable(); // زمان وقوع رویداد
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete(); // کاربر ثبت‌کننده رویداد
            $table->json('meta')->nullable(); // داده خام احتمالی شرکت حمل‌کننده
            $table->timestamps();

            $table->index(['shipment_id', 'occurred_at']); // نمایش مرتب تاریخچه
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
