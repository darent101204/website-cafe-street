<?php

use App\Http\Controllers\ProfileController;
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

// Checkout routes
Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'create'])->name('checkout.index');
Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'store'])->name('checkout.store');
Route::post('/checkout/setup', function (\Illuminate\Http\Request $request) {
    $orderType = $request->input('order_type', 'takeaway');
    
    // If a table QR was scanned, lock order type to dine_in
    if (session()->has('table_id') && session()->has('table_number')) {
        $orderType = 'dine_in';
    }
    
    session(['order_type' => $orderType]);
    
    return redirect()->route('checkout.index');
})->name('checkout.setup');
Route::get('/checkout/success', [App\Http\Controllers\OrderController::class, 'success'])->name('checkout.success');
Route::get('/order/track/{tracking_token}', [App\Http\Controllers\OrderController::class, 'track'])->name('order.track');

// Admin routes (Middleware will be added to this group later)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/payment', [App\Http\Controllers\AdminOrderController::class, 'updatePaymentStatus'])->name('orders.payment');

    // Kitchen Dashboard routes
    Route::get('/kitchen', [App\Http\Controllers\KitchenController::class, 'index'])->name('kitchen.index');
    Route::patch('/kitchen/{order}/status', [App\Http\Controllers\KitchenController::class, 'updateStatus'])->name('kitchen.status');
});

// -- BREEZE AUTH ROUTES --

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
