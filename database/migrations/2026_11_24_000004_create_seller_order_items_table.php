<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// این مهاجرت اقلام سفارش اصلی را به سفارش فرعی فروشنده متصل می‌کند.
return new class extends Migration {
    public function up(): void
    {
        Schema::create('seller_order_items', function (Blueprint $table) {
            $table->id(); // شناسه ردیف اتصال
            $table->foreignId('seller_order_id')->constrained('seller_orders')->cascadeOnDelete(); // سفارش فروشنده
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete(); // قلم سفارش اصلی
            $table->timestamps(); // زمان ثبت اتصال
            $table->unique('order_item_id'); // هر قلم سفارش فقط در یک سفارش فروشنده قرار می‌گیرد
        });
    }

    public function down(): void
    {
        // ابتدا اتصال اقلام حذف می‌شود.
        Schema::dropIfExists('seller_order_items');
    }
};
