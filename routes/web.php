<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Login sementara untuk testing (hapus setelah Melysa selesai Auth)
Route::get('/dev-login', function () {
    auth()->loginUsingId(6); // ID user admin
    return redirect('/admin/dashboard');
});

Route::get('/dev-logout', function () {
    auth()->logout();
    return 'Logged out';
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/merchants', [AdminController::class, 'merchants'])->name('merchants');
    Route::get('/consumers', [AdminController::class, 'consumers'])->name('consumers');
    Route::post('/merchants/{id}/approve', [AdminController::class, 'approveMerchant'])->name('merchants.approve');
    Route::post('/merchants/{id}/reject', [AdminController::class, 'rejectMerchant'])->name('merchants.reject');
});