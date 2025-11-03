<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CityController;

/*
|--------------------------------------------------------------------------
| API V1 Cities Routes
|--------------------------------------------------------------------------
|
| Cities related routes for API V1
|
*/

Route::prefix('cities')->group(function () {
    Route::get('/', [CityController::class, 'cities']);
    Route::get('/limited', [CityController::class, 'citiesLimited']);
    Route::get('/by-region/{regionId}', [CityController::class, 'citiesByRegion']);
});
