<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===============================
// HALAMAN UTAMA
// ===============================
Route::get('/', function () {
    return view('welcome');
});

// ===============================
// DASHBOARD
// ===============================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ===============================
// MERCHANT (AUTH)
// ===============================
Route::middleware('auth')->group(function () {

    // PRODUCT
    Route::resource('products', ProductController::class);

    // ORDER SYSTEM (PAKAI CONTROLLER KAMU)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/{status}', [OrderController::class, 'updateStatus'])->name('orders.update');

    // RIWAYAT (sementara)
    Route::get('/history', function () {
        return "Halaman Riwayat Penjualan";
    })->name('history.index');

    // PROFILE
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});


// ===============================
// MERCHANT REGISTER
// ===============================
Route::get('/merchant/register', function () {
    return view('merchant.register');
});


// ===============================
// DEV LOGIN (OPTIONAL)
// ===============================
Route::get('/dev-login', function () {
    auth()->loginUsingId(6);
    return redirect('/admin/dashboard');
});

Route::get('/dev-logout', function () {
    auth()->logout();
    return 'Logged out';
});


// ===============================
// ADMIN
// ===============================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {

    Route::get('/dashboard', [AdminController::class , 'dashboard'])->name('dashboard');
    Route::get('/merchants', [AdminController::class , 'merchants'])->name('merchants');
    Route::get('/consumers', [AdminController::class , 'consumers'])->name('consumers');

    Route::post('/merchants/{id}/approve', [AdminController::class , 'approveMerchant'])->name('merchants.approve');
    Route::post('/merchants/{id}/reject', [AdminController::class , 'rejectMerchant'])->name('merchants.reject');

    Route::get('/merchants/{id}', [AdminController::class , 'showMerchant'])->name('merchants.show');

    Route::get('/transactions', [AdminController::class , 'transactions'])->name('transactions');
    Route::get('/transactions/export', [AdminController::class , 'exportTransactions'])->name('transactions.export');
    Route::get('/transactions/export-pdf', [AdminController::class , 'exportTransactionsPdf'])->name('transactions.export-pdf');

    Route::get('/categories', [CategoryController::class , 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class , 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class , 'update'])->name('categories.update');
    Route::post('/categories/{category}/toggle', [CategoryController::class , 'toggleActive'])->name('categories.toggle');
    Route::delete('/categories/{category}', [CategoryController::class , 'destroy'])->name('categories.destroy');
});


// ===============================
// AUTH
// ===============================
require __DIR__ . '/auth.php';


// ===============================
// GOOGLE LOGIN
// ===============================
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/google/callback', function () {
    $user = Socialite::driver('google')->user();
    dd($user);
});


// ===============================
// CART
// ===============================
Route::post('/cart/add', [CartController::class , 'add']);
Route::get('/cart', [CartController::class , 'index']);
Route::put('/cart/{id}', [CartController::class , 'update']);
Route::delete('/cart/{id}', [CartController::class , 'delete']);