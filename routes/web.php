<?php

use App\Http\Controllers\Front\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\OrderController;

// Главная
Route::get('/', [HomeController::class, 'index'])->name('home');

// Страницы из Page по slug
Route::get('/about',        [PageController::class, 'show'])->name('about')->defaults('slug', 'about');
Route::get('/delivery',     [PageController::class, 'show'])->name('delivery')->defaults('slug', 'delivery');
Route::get('/install/ac',   [PageController::class, 'show'])->name('install.ac')->defaults('slug', 'install-ac');
Route::get('/install/vent', [PageController::class, 'show'])->name('install.vent')->defaults('slug', 'install-vent');
Route::get('/contacts',     [PageController::class, 'show'])->name('contacts')->defaults('slug', 'contacts');
Route::get('/privacy', [PageController::class, 'show'])->name('privacy')->defaults('slug', 'privacy');

// Каталог
Route::get('/catalogs', [CategoryController::class, 'all'])->name('categories.all');
Route::get('/catalog', [CategoryController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Товар

Route::get('/product/{product:slug}', [ProductController::class, 'show'])
    ->name('product.show');


Route::post('/lead/store', [LeadController::class, 'store'])->name('lead.store');


// страница корзины
Route::view('/cart', 'pages.basket')->name('basket');

// оформление заказа
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');


