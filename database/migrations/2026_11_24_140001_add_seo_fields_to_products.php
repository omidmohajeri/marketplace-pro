<?php
// افزودن فیلدهای سئو به محصولات
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void { Schema::table('products',function(Blueprint $t){$t->string('meta_title')->nullable();$t->string('meta_description',320)->nullable();$t->string('canonical_url')->nullable();});}
public function down():void {Schema::table('products',function(Blueprint $t){$t->dropColumn(['meta_title','meta_description','canonical_url']);});} };