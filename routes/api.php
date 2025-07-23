<?php

use App\Http\Controllers\ConvertationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CorsMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(CorsMiddleware::class)->prefix('user')->controller(UserController::class)->group(function () {
    Route::post('register', 'register')->name('user.register');
    Route::post('login', 'login')->name('user.login');
});

Route::middleware(['auth:sanctum', CorsMiddleware::class])->group(function () {
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('me', 'user')->name('user.me');
        Route::put('update', 'update')->name('user.update');
        Route::post('logout', 'logout')->name('user.logout');
    });

    Route::withoutMiddleware('auth:sanctum')->get('currency', [CurrencyController::class, 'get'])->name('currency.get');

    Route::prefix('convertation')->controller(ConvertationController::class)->group(function () {
        Route::get('/', 'get')->name('converatation.get');
        Route::post('buy', 'buy')->name('converatation.buy');
        Route::post('sell', 'sell')->name('converatation.sell');
        Route::post('calculate', 'calculate')->name('converatation.calculate');
    });
});
