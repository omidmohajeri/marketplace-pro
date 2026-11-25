<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('store_name'); $table->string('slug')->unique(); $table->text('description')->nullable();
            $table->enum('status',['pending','active','suspended'])->default('pending');
            $table->decimal('commission_rate',5,2)->default(10); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sellers'); }
};
