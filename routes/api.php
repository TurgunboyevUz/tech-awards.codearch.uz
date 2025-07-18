<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware(CorsMiddleware::class)->controller(UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware(['auth:sanctum', CorsMiddleware::class])->group(function () {

    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::put('/update', 'update');
        Route::post('/logout', 'logout');

        Route::get('/me', 'user');

        Route::post('/verify', 'verify');
        Route::post('/resend-otp', 'resendOtp');
        Route::post('/topup', 'topup');
    });

    Route::prefix('/currency')->controller(CurrencyController::class)->group(function () {
        Route::get('/', 'get');
    });
});
