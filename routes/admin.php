<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin\{
    AdminSetLocale,
    CheckRolePermission,
};

use App\Http\Controllers\Admin\{
    HomeController,
    AuthController,
    LanguageController,
    NotificationController,
    ProfileController,
    SettingController,
    RoleController,
    UserController,
};

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
                // logout route
                Route::any('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::middleware([CheckRolePermission::class])->group(function () {

                // home page route
                Route::get('/home', [HomeController::class, 'home'])->name('home');
                // profile routes
                Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');

                // notifications route
                Route::group(['prefix' => 'notifications',], function () {
                    Route::get('', [NotificationController::class, 'index'])->name('notifications.index');
                    Route::get('/send-email', [NotificationController::class, 'sendEmail'])->name('notifications.sendEmail');
                    Route::get('/send-sms', [NotificationController::class, 'sendSms'])->name('notifications.sendSms');
                    Route::get('/send-notification', [NotificationController::class, 'sendNotification'])->name('notifications.sendNotification');
                });

                // settings routes
                Route::group(['prefix' => 'settings',], function () {
                    Route::get('', [SettingController::class, 'index'])->name('settings.index');
                    Route::put('/update', [SettingController::class, 'update'])->name('settings.update');
                });

                // admins routes
                Route::resource('admins', RoleController::class);

                // roles routes
                Route::get('roles/form/{id?}', [RoleController::class, 'getForm'])->name('roles.getForm');
                Route::resource('roles', RoleController::class);

                // users routes
                Route::resource('users', UserController::class);

            });
        });
    });
