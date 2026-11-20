<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Transaction
    Route::apiResource('transactions', TransactionController::class);
});
