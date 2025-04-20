<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\PackSizeController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RetailerController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ScrapedProductController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/auth-check', fn() => response()->noContent());
    Route::get('/admin-check', [AuthController::class, 'isCurrentUserAdmin']);

    Route::middleware('ability:server:crud')->group(function () {
        Route::middleware('can:admin-crud')->group(function () {

            Route::apiResource('users', UserController::class);

            Route::apiResource('retailers', RetailerController::class)->except('show');

            Route::apiResource('currencies', CurrencyController::class)->except('show');

            Route::apiResource('roles', RoleController::class)->only('index');

            Route::apiResource('locations', LocationController::class)->only('index');
        });

        Route::middleware('can:user-crud')->group(function () {
            Route::get('/retailers/metrics', [RetailerController::class, 'metrics'])->name('retailers.metrics');
            Route::get('retailers/metrics/export', [RetailerController::class, 'exportMetrics']);
            Route::get('user-retailers', [RetailerController::class, 'getUserRetailers']);

            Route::apiResource('products', ProductController::class)->except('show');
            Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
            Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
            Route::get('scraped-products/export', [ScrapedProductController::class, 'export']);

            Route::apiResource('pack-sizes', PackSizeController::class)->except('show');
        });
    });

    Route::post('/scraped-products', [ScrapedProductController::class, 'store'])
        ->middleware('ability:scrapedProduct:store')
        ->name('scraped-products.store');
});
