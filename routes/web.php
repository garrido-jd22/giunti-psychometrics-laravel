<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', ProductSearchController::class)->name('products.search');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::view('/resources', 'pages.resources')->name('resources');

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::delete('/cart/items/{slug}', [CartController::class, 'destroy'])->name('cart.items.destroy');

Route::post('/contact', ContactController::class)->name('contact.store');
