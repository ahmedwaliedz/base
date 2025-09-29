<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Settings Routes
|--------------------------------------------------------------------------
|
| Settings related routes for API V1
|
*/

Route::prefix('settings')->group(function () {
    // Public settings routes
    Route::get('/public', function () {
        // Get public settings
    });
    
    // Admin settings routes
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('/', function () {
            // Get all settings
        });
        
        Route::put('/{key}', function (Request $request, $key) {
            // Update specific setting
        });
        
        Route::post('/', function (Request $request) {
            // Create new setting
        });
        
        Route::delete('/{key}', function ($key) {
            // Delete setting
        });
    });
});
