<?php
use Illuminate\Support\Facades\Route; use App\Http\Controllers\ReturnRequestController;
Route::middleware('auth')->post('/orders/{order}/returns',[ReturnRequestController::class,'store'])->name('orders.returns.store');