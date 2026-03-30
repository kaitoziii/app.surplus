<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/merchant/register', function () {
    return view('merchant.register');
});

Route::get('/', function () {
    return view('landing');
});