<?php

use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\NewsletterController;
use App\Http\Controllers\Store\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/newsletter', NewsletterController::class)->name('newsletter');
