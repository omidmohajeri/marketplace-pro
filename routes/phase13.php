<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SitePageController;
Route::get('/pages/{slug}',[SitePageController::class,'show'])->name('pages.show');