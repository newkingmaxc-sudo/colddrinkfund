<?php

use App\Http\Controllers\user\OnepayController;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiRechargeController;
use App\Http\Controllers\api\AdminApiController;
use Illuminate\Support\Facades\Route;

Route::get('/api-recharge/{type?}', [ApiRechargeController::class, 'api_recharge'])->name('api_recharge');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('number/{type}', [OnepayController::class, 'return_number']);
Route::get('deposit/submit', [OnepayController::class, 'depositSubmit']);

// Native Admin Mobile App REST API Endpoints
Route::prefix('admin')->group(function () {
    Route::post('login', [AdminApiController::class, 'login']);
    Route::get('dashboard', [AdminApiController::class, 'dashboard']);
    Route::get('deposits', [AdminApiController::class, 'deposits']);
    Route::post('deposits/action', [AdminApiController::class, 'depositAction']);
    Route::get('withdrawals', [AdminApiController::class, 'withdrawals']);
    Route::post('withdrawals/action', [AdminApiController::class, 'withdrawalAction']);
    Route::get('users', [AdminApiController::class, 'searchUser']);
    Route::post('users/manual-deposit', [AdminApiController::class, 'manualDeposit']);
    Route::get('payment-methods', [AdminApiController::class, 'paymentMethods']);
    Route::post('payment-methods/toggle', [AdminApiController::class, 'togglePaymentMethod']);
    Route::post('settings/emergency', [AdminApiController::class, 'emergencyToggle']);
});
