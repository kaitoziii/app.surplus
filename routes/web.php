<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// HALAMAN UTAMA
Route::get('/', function () {
    return view('welcome');
});

// PRODUCT DASHBOARD (MERCHANT)
Route::resource('products', ProductController::class);

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

    Route::resource('products', ProductController::class);
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