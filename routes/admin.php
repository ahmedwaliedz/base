<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\IntroPageController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AppNotificationController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\ReplayController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController;
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
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:admin.login')->name('login');
    });
    // authenticated routes
    Route::middleware('auth:admin')->group(function () {
        // logout route
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
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

            Route::group(['prefix' => 'app-notifications'], function () {
                Route::get('', [AppNotificationController::class, 'index'])->name('app-notifications.index');
                Route::post('{notification}/read', [AppNotificationController::class, 'markAsRead'])->name('app-notifications.markAsRead');
                Route::patch('{notification}/read', [AppNotificationController::class, 'markAsRead']);
                Route::post('mark-all-as-read', [AppNotificationController::class, 'markAllAsRead'])->name('app-notifications.markAllAsRead');
                Route::patch('mark-all-as-read', [AppNotificationController::class, 'markAllAsRead']);
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

            // pages routes
            Route::delete('pages/destroy-all', [PageController::class, 'destroyAll'])->name('pages.destroyAll');
            Route::put('pages/{id}/switch-type', [PageController::class, 'switchType'])->name('pages.switchType');
            Route::put('pages/{id}/restore', [PageController::class, 'restore'])->name('pages.restore');
            Route::resource('pages', PageController::class);

            // sliders routes
            Route::delete('sliders/destroy-all', [SliderController::class, 'destroyAll'])->name('sliders.destroyAll');
            Route::put('sliders/{id}/switch-active', [SliderController::class, 'switchActive'])->name('sliders.switchActive');
            Route::put('sliders/{id}/restore', [SliderController::class, 'restore'])->name('sliders.restore');
            Route::resource('sliders', SliderController::class);

            // faqs routes
            Route::delete('faqs/destroy-all', [FaqController::class, 'destroyAll'])->name('faqs.destroyAll');
            Route::put('faqs/{id}/switch-is-active', [FaqController::class, 'switchIsActive'])->name('faqs.switchIsActive');
            Route::put('faqs/{id}/restore', [FaqController::class, 'restore'])->name('faqs.restore');
            Route::resource('faqs', FaqController::class);

            // categories routes
            Route::delete('categories/destroy-all', [CategoryController::class, 'destroyAll'])->name('categories.destroyAll');
            Route::put('categories/{id}/switch-is-active', [CategoryController::class, 'switchIsActive'])->name('categories.switchIsActive');
            Route::put('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
            Route::resource('categories', CategoryController::class);

            // intro_pages routes
            Route::delete('intro-pages/destroy-all', [IntroPageController::class, 'destroyAll'])->name('intro-pages.destroyAll');
            Route::put('intro-pages/{id}/switch-is-active', [IntroPageController::class, 'switchIsActive'])->name('intro-pages.switchIsActive');
            Route::put('intro-pages/{id}/restore', [IntroPageController::class, 'restore'])->name('intro-pages.restore');
            Route::resource('intro-pages', IntroPageController::class);

            // posts routes
            Route::delete('posts/destroy-all', [PostController::class, 'destroyAll'])->name('posts.destroyAll');
            Route::put('posts/{id}/switch-is-active', [PostController::class, 'switchIsActive'])->name('posts.switchIsActive');
            Route::put('posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
            Route::resource('posts', PostController::class);

            // seo routes
            Route::delete('seo/destroy-all', [SeoController::class, 'destroyAll'])->name('seo.destroyAll');
            Route::put('seo/{id}/restore', [SeoController::class, 'restore'])->name('seo.restore');
            Route::resource('seo', SeoController::class);

            // socials routes
            Route::delete('socials/destroy-all', [SocialController::class, 'destroyAll'])->name('socials.destroyAll');
            Route::put('socials/{id}/switch-is-active', [SocialController::class, 'switchIsActive'])->name('socials.switchIsActive');
            Route::put('socials/{id}/restore', [SocialController::class, 'restore'])->name('socials.restore');
            Route::resource('socials', SocialController::class);

            // regions routes
            Route::delete('regions/destroy-all', [RegionController::class, 'destroyAll'])->name('regions.destroyAll');
            Route::put('regions/{id}/switch-is-active', [RegionController::class, 'switchIsActive'])->name('regions.switchIsActive');
            Route::put('regions/{id}/restore', [RegionController::class, 'restore'])->name('regions.restore');
            Route::resource('regions', RegionController::class);

            // cities routes
            Route::delete('cities/destroy-all', [CityController::class, 'destroyAll'])->name('cities.destroyAll');
            Route::put('cities/{id}/switch-is-active', [CityController::class, 'switchIsActive'])->name('cities.switchIsActive');
            Route::put('cities/{id}/restore', [CityController::class, 'restore'])->name('cities.restore');
            Route::resource('cities', CityController::class);

            // districts routes
            Route::delete('districts/destroy-all', [DistrictController::class, 'destroyAll'])->name('districts.destroyAll');
            Route::put('districts/{id}/switch-is-active', [DistrictController::class, 'switchIsActive'])->name('districts.switchIsActive');
            Route::put('districts/{id}/restore', [DistrictController::class, 'restore'])->name('districts.restore');
            Route::resource('districts', DistrictController::class);

            // contact_messages routes
            Route::delete('contact-messages/destroy-all', [ContactMessageController::class, 'destroyAll'])->name('contact-messages.destroyAll');
            Route::put('contact-messages/{id}/switch-is-read', [ContactMessageController::class, 'switchIsRead'])->name('contact-messages.switchIsRead');
            Route::put('contact-messages/{id}/restore', [ContactMessageController::class, 'restore'])->name('contact-messages.restore');
            Route::resource('contact-messages', ContactMessageController::class)->except(['create', 'store', 'edit', 'update']);

            // complaints routes
            Route::delete('complaints/destroy-all', [ComplaintController::class, 'destroyAll'])->name('complaints.destroyAll');
            Route::put('complaints/{id}/switch-is-read', [ComplaintController::class, 'switchIsRead'])->name('complaints.switchIsRead');
            Route::put('complaints/{id}/switch-status', [ComplaintController::class, 'switchStatus'])->name('complaints.switchStatus');
            Route::put('complaints/{id}/restore', [ComplaintController::class, 'restore'])->name('complaints.restore');
            Route::resource('complaints', ComplaintController::class)->except(['create', 'store', 'edit', 'update']);

            // replays routes
            Route::delete('replays/destroy-all', [ReplayController::class, 'destroyAll'])->name('replays.destroyAll');
            Route::put('replays/{id}/restore', [ReplayController::class, 'restore'])->name('replays.restore');
            Route::resource('replays', ReplayController::class)->except(['create', 'store', 'edit', 'update']);

        });
    });
});
