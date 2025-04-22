<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    HomeController,
    AuthController,
    LanguageController,
    NotificationController,
    ProfileController,
    SettingController,
};
use App\Http\Middleware\Admin\AdminSetLocale;

    // change lang routes
    Route::get('lang/{lang}',[LanguageController::class, 'changeLang'])->name('lang.change');

    // route group to set admin lang middleware
    Route::middleware([AdminSetLocale::class , 'web'])->group(function () {

        // guest routes
        Route::middleware('guest:admin')->group(function () {
            // login page
            Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
            // login request
            Route::post('/login', [AuthController::class, 'login'])->name('login');
        });

        // authenticated routes
        Route::middleware('auth:admin')->group(function () {
            // home page route
            Route::get('/home', [HomeController::class, 'home'])->name('home');
            // logout route
            Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

            // notifications route
            Route::any('/notifications', [NotificationController::class, 'notifications'])->name('notifications');
            // profile routes
            Route::any('/profile', [ProfileController::class, 'profile'])->name('profile');
            // settings routes
            Route::any('/settings', [SettingController::class, 'settings'])->name('settings');

            // try route resource
            Route::resource('admins', SettingController::class)->names('admins');
            Route::resource('users', SettingController::class)->names('users');
            Route::resource('clients', SettingController::class)->names('clients');
        });
    });




