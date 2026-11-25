<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\CustomerOrderController;

Route::get('/language/{locale}', [LocaleController::class, 'switch'])
    ->whereIn('locale', ['fa', 'en'])->name('locale.switch');

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/products', [StorefrontController::class, 'products'])->name('products.index');
Route::get('/products/{slug}', [StorefrontController::class, 'product'])->name('products.show');

// مسیرهای یکپارچه ماژول‌های فاز 2 تا 17
require_once __DIR__.'/marketplace-modules.php';

Route::middleware('auth')->get('/account/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
