<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;


Route::prefix('stores')->group(function () {
    Route::get('/nearby', [StoreController::class , 'nearby']);
    Route::get('/{store}', [StoreController::class , 'show']);
    Route::get('/{store}/products', [ProductController::class , 'byStore'])
        ->whereNumber('store');
});

Route::prefix('products')->group(function () {
    Route::get('/urgent', [ProductController::class , 'urgent']);
    Route::get('/{product}', [ProductController::class , 'show']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/cart/add', [CartController::class, 'add']);
Route::get('/cart', [CartController::class, 'index']);
Route::put('/cart/{id}', [CartController::class, 'update']);
Route::delete('/cart/{id}', [CartController::class, 'delete']);