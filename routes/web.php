<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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



// Route::get('/auth/google', function () {
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/auth/google/callback', function () {
//     $user = Socialite::driver('google')->user();

//     dd($user); // test dulu
// });

Route::get('/auth/google', [SocialiteController::class, 'redirect']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
Route::get('/choose-role', function () {
    return view('auth.choose-role');
})->middleware('auth');

Route::post('/save-role', function (Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();

    if (! $user) {
        abort(403);
    }

    $user->role = $request->role;
    $user->save();

    return redirect('/dashboard');
})->middleware('auth');

Route::get('/password', function () {
    return view('profile.password');
})->middleware(['auth'])->name('password.edit');