<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable(); $table->string('country_code',2)->default('IR');
            $table->string('province'); $table->string('city'); $table->text('address_line');
            $table->string('postal_code',30)->nullable(); $table->string('recipient_name'); $table->string('recipient_phone',30);
            $table->boolean('is_default')->default(false); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('addresses'); }
};
