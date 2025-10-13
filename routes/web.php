<?php

use App\Http\Controllers\Front\LeadController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\CategoryController;
use App\Http\Controllers\Front\ProductController;


// Главная
Route::get('/', [HomeController::class, 'index'])->name('home');

// Страницы из Page по slug
Route::get('/about',        [PageController::class, 'show'])->name('about')->defaults('slug', 'about');
Route::get('/delivery',     [PageController::class, 'show'])->name('delivery')->defaults('slug', 'delivery');
Route::get('/install/ac',   [PageController::class, 'show'])->name('install.ac')->defaults('slug', 'install-ac');
Route::get('/install/vent', [PageController::class, 'show'])->name('install.vent')->defaults('slug', 'install-vent');
Route::get('/contacts',     [PageController::class, 'show'])->name('contacts')->defaults('slug', 'contacts');

// Каталог
Route::get('/catalog', [CategoryController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Товар
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('catalog.product');


Route::post('/lead/store', [LeadController::class, 'store'])->name('lead.store');

Route::get('/catalogs', [CategoryController::class, 'all'])->name('categories.all');
// routes/web.php
Route::get('/privacy', [PageController::class, 'show'])
    ->name('privacy')
    ->defaults('slug', 'privacy');

