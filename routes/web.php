<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🔥 HALAMAN UTAMA
Route::get('/', function () {
    return view('welcome');
});

// Login sementara untuk testing (hapus nanti)
Route::get('/dev-login', function () {
    auth()->loginUsingId(6);
    return redirect('/admin/dashboard');
});

Route::get('/dev-logout', function () {
    auth()->logout();
    return 'Logged out';
});

// 🔥 ADMIN
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/merchants', [AdminController::class, 'merchants'])->name('merchants');
    Route::get('/consumers', [AdminController::class, 'consumers'])->name('consumers');

    Route::post('/merchants/{id}/approve', [AdminController::class, 'approveMerchant'])->name('merchants.approve');
    Route::post('/merchants/{id}/reject', [AdminController::class, 'rejectMerchant'])->name('merchants.reject');
    Route::get('/merchants/{id}', [AdminController::class, 'showMerchant'])->name('merchants.show');

    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [AdminController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/transactions/export-pdf', [AdminController::class, 'exportTransactionsPdf'])->name('transactions.export-pdf');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggleActive'])->name('categories.toggle');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// 🔥 MERCHANT REGISTER
Route::get('/merchant/register', function () {
    return view('merchant.register');
});

// 🔥 DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔥 PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔥 AUTH ROUTES
require __DIR__ . '/auth.php';

// 🔥 GOOGLE LOGIN
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    $user = Socialite::driver('google')->user();
    dd($user);
});

// 🔥 CART
Route::middleware('auth')->group(function () {
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');
});

// 🔥 DETAIL PRODUK
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

// 🔥 FAVORITES
Route::get('/favorites', function () {
    return view('product.favorites-product');
});

// 🔥 CHECKOUT
Route::middleware('auth')->group(function () {
    // GET checkout page
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
   
});
