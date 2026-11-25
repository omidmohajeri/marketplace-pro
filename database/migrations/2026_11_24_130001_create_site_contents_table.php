<?php
// جدول صفحات و بنرهای قابل مدیریت
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void { Schema::create('site_contents',function(Blueprint $t){
$t->id(); $t->string('type',20); // page یا banner
$t->string('title'); $t->string('slug')->unique(); $t->longText('body')->nullable(); // محتوای صفحه
$t->string('image_path')->nullable(); $t->string('target_url')->nullable(); // تصویر و مقصد بنر
$t->timestamp('starts_at')->nullable(); $t->timestamp('ends_at')->nullable(); $t->boolean('is_active')->default(true); $t->timestamps();
}); } public function down():void { Schema::dropIfExists('site_contents'); } };