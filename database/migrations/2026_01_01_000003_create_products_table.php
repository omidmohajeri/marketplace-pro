<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->foreignId('seller_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('sku')->unique(); $table->string('slug')->unique(); $table->json('name'); $table->json('description')->nullable();
            $table->unsignedBigInteger('price'); $table->unsignedBigInteger('compare_at_price')->nullable();
            $table->unsignedInteger('stock')->default(0); $table->enum('status',['draft','active','archived'])->default('draft');
            $table->json('attributes')->nullable(); $table->timestamp('published_at')->nullable(); $table->timestamps();
            $table->index(['status','published_at']); $table->index(['category_id','status']);
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
