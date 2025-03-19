<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RetailerController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('retailers')->name('retailers.')->group(function () {
        Route::get('/', [RetailerController::class, 'index'])->name('index');
        Route::post('/', [RetailerController::class, 'store'])->name('store');
        Route::patch('/{retailer}', [RetailerController::class, 'update'])->name('update');
        Route::delete('/{retailer}', [RetailerController::class, 'destroy'])->name('destroy');
    });
});
