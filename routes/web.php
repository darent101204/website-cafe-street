<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Static pages
Route::get('/delivery', [App\Http\Controllers\PageController::class, 'delivery'])->name('delivery');
Route::get('/about', [App\Http\Controllers\PageController::class, 'about'])->name('about');

// Product CRUD routes
Route::resource('products', App\Http\Controllers\ProductController::class);

// Search route
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

// Cart routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

// Table QR routing
Route::get('/table/{token}', [App\Http\Controllers\TableController::class, 'access'])->name('table.access');
