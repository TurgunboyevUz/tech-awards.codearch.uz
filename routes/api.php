<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Support\Facades\Route;

// Public routes (no auth required)
Route::middleware(CorsMiddleware::class)->prefix('user')->controller(UserController::class)->group(function () {
    Route::post('register', 'register')->name('user.register');
    Route::post('login', 'login')->name('user.login');
});

// Protected routes (auth + CORS)
Route::middleware(['auth:sanctum', CorsMiddleware::class])->group(function () {

    // User-related routes
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('me', 'user')->name('user.me');
        Route::put('update', 'update')->name('user.update');
        Route::post('logout', 'logout')->name('user.logout');
    });

    Route::prefix('payment')->controller(PaymentController::class)->group(function () {
        Route::post('create', 'create')->name('payment.create');
        Route::get('transactions', 'transactions')->name('payment.transactions');
    });

    // Currency-related routes
    Route::prefix('currency')->controller(CurrencyController::class)->group(function () {
        Route::get('/', 'get')->name('currency.get');
        Route::get('/calculate', 'calculate')->name('currency.calculate');
    });
});
