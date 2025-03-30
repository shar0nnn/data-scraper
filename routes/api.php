<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\PackSizeController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RetailerController;
use App\Http\Controllers\API\ScrapedProductController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/auth-check', fn() => response()->noContent());

    Route::middleware('ability:server:crud')->group(function () {
        Route::apiResource('retailers', RetailerController::class)->except('show');
        Route::apiResource('products', ProductController::class)->except('show');
        Route::apiResource('currencies', CurrencyController::class)->except('show');
        Route::apiResource('pack-sizes', PackSizeController::class)->except('show');
        Route::get('/retailers/metrics', [RetailerController::class, 'metrics'])->name('metrics');
    });

    Route::post('/scraped-products', [ScrapedProductController::class, 'store'])
        ->middleware('ability:scrapedProduct:store')
        ->name('scraped-products.store');
});
