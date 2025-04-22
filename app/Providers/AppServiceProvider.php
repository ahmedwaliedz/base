<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin.login', function ($request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        $this->loadMigrationsFrom(
            collect(File::directories(database_path('migrations')))
                ->prepend(database_path('migrations'))
                ->toArray()
        );


    }
}
