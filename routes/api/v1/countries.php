<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CountriesController;

/*
|--------------------------------------------------------------------------
| API V1 Countries Routes
|--------------------------------------------------------------------------
|
| Countries, cities, and regions related routes for API V1
|
*/

Route::prefix('countries')->group(function () {
    Route::get('/', [CountriesController::class, 'countries']);
    Route::get('/limited', [CountriesController::class, 'countriesLimited']);
    Route::get('/with-regions', [CountriesController::class, 'countriesWithRegions']);
});

Route::prefix('cities')->group(function () {
    Route::get('/', [CountriesController::class, 'cities']);
    Route::get('/with-regions', [CountriesController::class, 'citiesWithRegions']);
});

Route::prefix('regions')->group(function () {
    Route::get('/', [CountriesController::class, 'regions']);
});
