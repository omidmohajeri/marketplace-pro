<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('number')->unique(); $table->enum('status',['pending','paid','processing','shipped','delivered','cancelled','refunded'])->default('pending');
            $table->string('currency',3)->default('IRR'); $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount_total')->default(0); $table->unsignedBigInteger('shipping_total')->default(0);
            $table->unsignedBigInteger('grand_total'); $table->json('shipping_address'); $table->json('billing_address')->nullable();
            $table->timestamp('placed_at')->nullable(); $table->timestamps(); $table->index(['user_id','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
