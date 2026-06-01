<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Api\AuthController;
use App\Http\Controllers\User\Api\DashboardController;
use App\Http\Controllers\User\Api\ProfileController;
use App\Http\Controllers\User\Api\CaseController;
use App\Http\Controllers\User\Api\ChallanController;
use App\Http\Controllers\User\Api\WalletController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth:sanctum')->group(function () {

    //AUTH API
    Route::post('/logout', [AuthController::class, 'logout']);

    //DASHBOARD API
    Route::get('/dashboard-stats', [DashboardController::class, 'dashboardStats']);

    Route::get('/recent-cases', [DashboardController::class, 'recentCases']);

    Route::get('/recent-challans', [DashboardController::class, 'recentChallans']);

    //PROFILE API
    Route::get('/profile', [ProfileController::class, 'profile']);

    Route::post('/update-profile', [ProfileController::class, 'updateProfile']);

    Route::post('/update-settings', [ProfileController::class, 'updateSettings']);


    //CASE API

    Route::post('/cases', [CaseController::class, 'store']);

    Route::get('/cases', [CaseController::class, 'index']);

    Route::get('/cases/{id}', [CaseController::class, 'show']);

    Route::post('/cases/{id}', [CaseController::class, 'update']);

    //CHALLAN API

    Route::get('/challans', [ChallanController::class, 'index']);
    // Route::post('/challans', [ChallanController::class, 'store']);

    // Route::post('/challans/{id}/status', [ChallanController::class, 'updateStatus']);

    //WALLET API

    Route::get('/wallet', [WalletController::class, 'index']);

    Route::get('/transactions', [WalletController::class, 'transactions']);

    Route::post('/withdraw-request', [WalletController::class, 'withdrawRequest']);
});
