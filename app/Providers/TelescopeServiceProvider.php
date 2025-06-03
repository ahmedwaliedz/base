<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            // Filter out health check requests and other non-essential entries
            if ($entry->type === 'request' && $entry->content['uri'] === '/health-check') {
                return false;
            }

            // Filter out specific routes or entry types that don't need monitoring
            if ($entry->type === 'query' && str_contains($entry->content['query'] ?? '', 'telescope_')) {
                return false;
            }

            return true; // Show all other requests in the dashboard
        });

        // Make sure to call the parent register method to register the Telescope migrations
        parent::register();
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            // Only allow users with specific email domains or admin users
            if (in_array($user->email, config('telescope.allowed_emails', []))) {
                return true;
            }

            // Check for admin role or specific permission
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }

            return false; // Deny access to other users
        });
    }
}
