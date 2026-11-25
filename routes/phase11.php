<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductSearchController;
Route::get('/search', ProductSearchController::class)->name('products.search'); // جست‌وجوی عمومی