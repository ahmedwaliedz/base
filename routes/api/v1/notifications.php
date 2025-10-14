<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Notifications Routes
|--------------------------------------------------------------------------
|
| Notification related routes for API V1
|
*/

Route::prefix('notifications')->middleware(['auth'])->group(function () {
    // Notification routes will be implemented here
});
