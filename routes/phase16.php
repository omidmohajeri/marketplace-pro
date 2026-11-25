<?php
use Illuminate\Support\Facades\Route; use App\Http\Controllers\SupportTicketController;
Route::middleware('auth')->prefix('support')->name('support.')->group(function(){Route::get('/',[SupportTicketController::class,'index'])->name('index');Route::post('/',[SupportTicketController::class,'store'])->name('store');});