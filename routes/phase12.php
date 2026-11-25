<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
Route::middleware('auth')->prefix('account/notifications')->name('notifications.')->group(function(){
 Route::get('/',[NotificationController::class,'index'])->name('index');
 Route::patch('/{id}/read',[NotificationController::class,'read'])->name('read');
 Route::post('/read-all',[NotificationController::class,'readAll'])->name('read-all');
});