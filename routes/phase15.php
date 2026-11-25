<?php
use Illuminate\Support\Facades\Route; use App\Http\Controllers\Admin\AnalyticsController;
Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->get('/analytics',[AnalyticsController::class,'index'])->name('analytics');