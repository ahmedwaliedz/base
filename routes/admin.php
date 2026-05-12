<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\Admin\AdminSetLocale;
use App\Http\Middleware\Admin\CheckRolePermission;
use Illuminate\Support\Facades\Route;

// change lang routes
Route::get('lang/{lang}', [LanguageController::class, 'changeLang'])->name('lang.change');

// route group to set admin lang middleware
Route::middleware([AdminSetLocale::class, 'web'])->group(function () {
    // guest routes
    Route::middleware('guest:admin')->group(function () {
        // login page
        Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
        // login request
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });
    // authenticated routes
    Route::middleware('auth:admin')->group(function () {
        // logout route
        Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
        // profile routes
        Route::group(['prefix' => 'profile'], function () {
            Route::get('', [ProfileController::class, 'profile'])->name('profile');
            Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
        });

        Route::middleware([CheckRolePermission::class])->group(function () {
            // home page route
            Route::get('/home', [HomeController::class, 'home'])->name('home');
            // notifications route
            Route::group(['prefix' => 'notifications'], function () {
                Route::get('', [NotificationController::class, 'index'])->name('notifications.index');
                Route::get('/send-email', [NotificationController::class, 'sendEmail'])->name('notifications.sendEmail');
                Route::get('/send-sms', [NotificationController::class, 'sendSms'])->name('notifications.sendSms');
                Route::post('/send-notifications', [NotificationController::class, 'sendNotifications'])->name('notifications.sendNotifications');
            });

            // settings routes
            Route::group(['prefix' => 'settings'], function () {
                Route::get('', [SettingController::class, 'index'])->name('settings.index');
                Route::put('/update', [SettingController::class, 'update'])->name('settings.update');
            });

            // admins routes
            Route::delete('admins/destroy-all', [AdminController::class, 'destroyAll'])->name('admins.destroyAll');
            Route::put('admins/{id}/switch-block', [AdminController::class, 'switchBlock'])->name('admins.switchBlock');
            Route::put('admins/{id}/restore', [AdminController::class, 'restore'])->name('admins.restore');
            Route::get('admins/statistics', [AdminController::class, 'statistics'])->name('admins.statistics');
            Route::resource('admins', AdminController::class);

            // users routes
            Route::delete('users/destroy-all', [UserController::class, 'destroyAll'])->name('users.destroyAll');
            Route::put('users/{id}/switch-block', [UserController::class, 'switchBlock'])->name('users.switchBlock');
            Route::put('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
            Route::get('users/statistics', [UserController::class, 'statistics'])->name('users.statistics');
            Route::get('users/diagrams', [UserController::class, 'diagrams'])->name('users.diagrams');
            Route::resource('users', UserController::class);

            // countries routes
            Route::delete('countries/destroy-all', [CountryController::class, 'destroyAll'])->name('countries.destroyAll');
            Route::put('countries/{id}/switch-active', [CountryController::class, 'switchActive'])->name('countries.switchActive');
            Route::put('countries/{id}/restore', [CountryController::class, 'restore'])->name('countries.restore');
            Route::get('countries/statistics', [CountryController::class, 'statistics'])->name('countries.statistics');
            Route::resource('countries', CountryController::class);

            // roles routes
            Route::get('roles/statistics', [RoleController::class, 'statistics'])->name('roles.statistics');
            Route::get('roles/form/{id?}', [RoleController::class, 'getForm'])->name('roles.getForm');
            Route::resource('roles', RoleController::class);

        });
    });
});
