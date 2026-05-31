<?php

namespace App\Providers;

use App\Support\PhoneNormalizer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Auth service bindings
        $this->app->bind(
            \App\Contracts\AuthServiceInterface::class,
            \App\Services\Auth\AuthService::class
        );

        $this->app->bind(
            \App\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        $this->app->bind(
            \App\Contracts\CodeSenderInterface::class,
            \App\Services\CodeSender\LogCodeSender::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        RateLimiter::for('admin.login', function ($request) {
            return Limit::perMinute(5)
                ->by(strtolower(trim((string) $request->input('email'))) . '|' . $request->ip());
        });

        // Rate limiter for request-code endpoint
        RateLimiter::for('request-code', function ($request) {
            $maxAttempts = config('auth_codes.rate_limit.max_attempts', 3);
            $decayMinutes = config('auth_codes.rate_limit.decay_minutes', 1);

            return Limit::perMinutes($decayMinutes, $maxAttempts)
                ->by($request->ip() . '|' . (PhoneNormalizer::normalize($request->input('phone')) ?: 'unknown'));
        });

        $this->loadMigrationsFrom(
            collect(File::directories(database_path('migrations')))
                ->prepend(database_path('migrations'))
                ->toArray()
        );
        View::composer([
            'admin.layouts.parts.notifications',
        ], function ($view) {
            $admin = auth('admin')->user();

            if (! $admin) {
                $view->with([
                    'adminNotificationSummary' => [
                        'total' => 0,
                        'unread' => 0,
                        'read' => 0,
                    ],
                    'adminLatestNotifications' => collect(),
                ]);

                return;
            }

            $data = app(\App\Services\Admin\AppNotificationService::class)->dashboardData($admin);
            $view->with($data);
        });

        Model::shouldBeStrict(! app()->isProduction());

    }
}
