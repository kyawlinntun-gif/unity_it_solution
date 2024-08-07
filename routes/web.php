<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// Item routes

Route::get('/', function() {
    return redirect()->route('items.index');
});
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{id}', [ItemController::class, 'show'])->where('id', '[0-9]+')->name('items.show');

// End Item routes

// For User
Route::middleware('auth')->group(function() {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::match(['put', 'patch'], '/user', [UserController::class, 'update'])->name('user.update');

    // Cart
    Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->where('id', '[0-9]+')->name('cart.destroy');
    // End Cart

    // Export Excel
    Route::get('/carts/export', [CartController::class, 'export'])->name('cart.export');
    // End Export Excel

    // Paid for items
    Route::post('/paid', [CartController::class, 'paid'])->name('carts.paid');
    // End Paid for items
});
// End User

// For Admin
Route::middleware(['auth', 'admin'])->group(function() {
    // Item routes
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::match(['put', 'patch'], '/items/{id}', [ItemController::class, 'update'])->where('id', '[0-9]+')->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->where('id', '[0-9]+')->name('items.destroy');
    // End Item routes

    // Export Excel
    Route::get('/items/export', [ItemController::class, 'export'])->name('items.export');
    Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('/orders/export', [OrderController::class, 'export'])->name('orders.export');
    // End Export Excel

    // Customer routes
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->where('id', '[0-9]+')->name('customers.destroy');
    // End Customer routes

    // Order Lists
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    // End Order Lists
});
// End Admin

