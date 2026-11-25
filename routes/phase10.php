<?php
// مسیرها را به routes/web.php منتقل و دسترسی‌ها را با Policy پروژه محدود کنید
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\Admin\ReviewModerationController;
Route::middleware('auth')->group(function () {
 Route::post('/products/{product}/reviews',[ProductReviewController::class,'store'])->name('products.reviews.store');
 Route::post('/reviews/{review}/report',[ProductReviewController::class,'report'])->name('reviews.report');
 Route::post('/products/{product}/questions',[ProductQuestionController::class,'store'])->name('products.questions.store');
 Route::post('/questions/{question}/answer',[ProductQuestionController::class,'answer'])->name('products.questions.answer');
});
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
 Route::get('/moderation',[ReviewModerationController::class,'index'])->name('moderation.index');
 Route::patch('/moderation/reviews/{review}',[ReviewModerationController::class,'review'])->name('moderation.reviews.update');
 Route::patch('/moderation/questions/{question}',[ReviewModerationController::class,'question'])->name('moderation.questions.update');
 Route::patch('/moderation/reports/{report}',[ReviewModerationController::class,'report'])->name('moderation.reports.update');
});