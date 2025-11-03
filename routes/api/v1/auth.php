<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Auth\RequestCodeController;
use App\Http\Controllers\Api\V1\Auth\LoginPasswordController;
use App\Http\Controllers\Api\V1\Auth\LoginCodeController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;

/*
|--------------------------------------------------------------------------
| API V1 Auth Routes
|--------------------------------------------------------------------------
|
| Authentication related routes for API V1
|
*/
// Route::post('auth/login-with-password', [LoginPasswordController::class, 'login']);
Route::prefix('auth')->group(function () {
    
    // Guest routes (no authentication required)

    Route::middleware('guest')->group(function () {
        // Login with password
        Route::post('/login-with-password', [LoginPasswordController::class, 'login']);


        
        // Login with activation code
        Route::post('/login-code', [LoginCodeController::class, 'loginCode']);
        // Request activation code
        Route::post('/request-code', [RequestCodeController::class, 'requestCode'])->middleware('throttle:request-code');
    
    });

    // Authenticated routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logout']);
        Route::get('/me', [MeController::class, 'me'])->middleware('complete.info');
    });
});
