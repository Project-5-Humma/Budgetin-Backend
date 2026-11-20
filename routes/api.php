<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\SavingsGoalController;
use App\Http\Controllers\Api\SavingsDepositController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // Transaction
    Route::post('transactions', [TransactionController::class, 'store']);

     // Savings Deposits
    Route::get('savings-goals/{savingsGoalId}/deposits',[SavingsDepositController::class, 'index']
    );

    Route::post('savings-goals/{savingsGoalId}/deposits',[SavingsDepositController::class, 'store']
    );

   Route::apiResource('savings-goals', SavingsGoalController::class);
});
