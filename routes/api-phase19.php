<?php
// محتوا را در routes/api.php ادغام کنید؛ پیش‌نیاز: Laravel Sanctum
use Illuminate\Support\Facades\Route; use App\Http\Controllers\Api\V1\ProductController; use App\Http\Controllers\Api\V1\MeController;
Route::prefix('v1')->name('api.v1.')->group(function(){
 Route::get('/products',[ProductController::class,'index'])->name('products.index');
 Route::get('/products/{product}',[ProductController::class,'show'])->name('products.show');
 Route::middleware('auth:sanctum')->get('/me',MeController::class)->name('me');
});