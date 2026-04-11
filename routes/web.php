<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===============================
// HALAMAN UTAMA
// ===============================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ===============================
// DASHBOARD
// ===============================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ===============================
// DEV LOGIN (hapus saat production)
// ===============================
Route::get('/dev-login/admin', function () {
    auth()->loginUsingId(1);
    return redirect('/admin/dashboard');
})->name('dev.admin');

Route::get('/dev-login/consumer', function () {
    $consumer = \App\Models\User::where('role', 'consumer')->first();
    auth()->login($consumer);
    return redirect()->route('home');
})->name('dev.consumer');

Route::get('/dev-login/admin', function () {
    $admin = \App\Models\User::where('role', 'admin')->first();
    auth()->login($admin);
    return redirect()->route('admin.dashboard');
})->name('dev.admin');

Route::get('/dev-login/merchant', function () {
    $merchant = \App\Models\User::where('role', 'merchant')->first();
    auth()->login($merchant);
    return redirect('/products');
})->name('dev.merchant');

Route::get('/dev-login/merchant', function () {
    auth()->loginUsingId(3);
    return redirect('/products');
})->name('dev.merchant');

Route::get('/dev-logout', function () {
    auth()->logout();
    return redirect('/login');
})->name('dev.logout');

// ===============================
// ADMIN
// ===============================
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
    try {
        $googleUser = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Login Google gagal, coba lagi.');
    }

    $user = User::where('email', $googleUser->getEmail())->first();

    if ($user) {
        auth()->login($user, true);
    } else {
        $user = User::create([
            'name'              => $googleUser->getName(),
            'email'             => $googleUser->getEmail(),
            'password'          => bcrypt(\Illuminate\Support\Str::random(16)),
            'role'              => 'consumer',
            'email_verified_at' => now(),
        ]);
        auth()->login($user, true);
    }

    return redirect('/');
});

// ===============================
// CART
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/add', [CartController::class, 'add']); // fallback lama
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'delete'])->name('cart.delete');
});

// ===============================
// CHECKOUT
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

// ===============================
// CONSUMER ORDERS
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders.index');
    Route::get('/history', [OrderController::class, 'history'])->name('history.index');
});

// ===============================
// MERCHANT
// ===============================
Route::get('/merchant/register', function () {
    return view('merchant.register');
});

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

// ===============================
// PRODUCT & STORE DETAIL
// ===============================
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/store/{id}', [HomeController::class, 'storeDetail'])->name('store.detail');

// ===============================
// PROFILE
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===============================
// FAVORITES
// ===============================
Route::get('/favorites', function () {
    return view('product.favorites-product');
});