<?php
// جدول تیکت‌های پشتیبانی
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void {Schema::create('support_tickets',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->string('subject');$t->string('category',60)->default('general');$t->enum('priority',['low','normal','high'])->default('normal');$t->enum('status',['open','in_progress','closed'])->default('open');$t->timestamps();});}
public function down():void {Schema::dropIfExists('support_tickets');} };