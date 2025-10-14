<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\RegionController;

/*
|--------------------------------------------------------------------------
| API V1 Regions Routes
|--------------------------------------------------------------------------
|
| Regions related routes for API V1
|
*/

Route::prefix('regions')->group(function () {
    Route::get('/', [RegionController::class, 'regions']);
    Route::get('/limited', [RegionController::class, 'regionsLimited']);
    Route::get('/with-cities', [RegionController::class, 'regionsWithCities']);
});
