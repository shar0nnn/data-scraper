<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\PackSizeController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RetailerController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('retailers')->name('retailers.')->group(function () {
        Route::get('/', [RetailerController::class, 'index'])->name('index');
        Route::post('/', [RetailerController::class, 'store'])->name('store');
        Route::patch('/{retailer}', [RetailerController::class, 'update'])->name('update');
        Route::delete('/{retailer}', [RetailerController::class, 'destroy'])->name('destroy');
        Route::get('/metrics', [RetailerController::class, 'metrics'])->name('metrics');
    });
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::patch('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('pack-sizes')->name('pack-sizes.')->group(function () {
        Route::get('/', [PackSizeController::class, 'index'])->name('index');
        Route::post('/', [PackSizeController::class, 'store'])->name('store');
        Route::patch('/{packSize}', [PackSizeController::class, 'update'])->name('update');
        Route::delete('/{packSize}', [PackSizeController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('currencies')->name('currencies.')->group(function () {
        Route::get('/', [CurrencyController::class, 'index'])->name('index');
        Route::post('/', [CurrencyController::class, 'store'])->name('store');
        Route::patch('/{currency}', [CurrencyController::class, 'update'])->name('update');
        Route::delete('/{currency}', [CurrencyController::class, 'destroy'])->name('destroy');
    });
});
