<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔥 HALAMAN UTAMA
Route::get('/', function () {
    return view('welcome');
});

// 🔥 TAMBAHAN: MERCHANT REGISTER (INI YANG KAMU BUTUH)
Route::get('/merchant/register', function () {
    return view('merchant.register');
});

// 🔥 DASHBOARD (LOGIN REQUIRED)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔥 PROFILE (LOGIN REQUIRED)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔥 AUTH ROUTES (LOGIN, REGISTER USER)
require __DIR__.'/auth.php';

use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    $user = Socialite::driver('google')->user();

    dd($user); // test dulu
});

Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'index']);
Route::put('/cart/{id}', [CartController::class, 'update']);
Route::delete('/cart/{id}', [CartController::class, 'delete']);

Route::get('/cart-page', function () {
    return view('cart');
});

Route::get('/product', function () {
    return view('product.detail-product');
});