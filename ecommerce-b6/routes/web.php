<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Users
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |-----------------
    | Shopping Cart
    |-----------------
    */

    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart');

    Route::post('/cart/add', [CartController::class, 'add'])
        ->name('cart.add');

    Route::delete('/cart/{id}', [CartController::class, 'remove'])
        ->name('cart.remove');

    /*
    |-----------------
    | Checkout
    |-----------------
    */

    Route::post('/checkout', [CheckoutController::class, 'store'])
        ->name('checkout');

    /*
    |-----------------
    | Profile
    |-----------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Only
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('products', ProductController::class)
        ->except(['show']);

    Route::resource('categories', CategoryController::class);
});


/*
|--------------------------------------------------------------------------
| Public Product Pages (User)
|--------------------------------------------------------------------------
*/

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');



/*
|--------------------------------------------------------------------------
| Authentication Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
