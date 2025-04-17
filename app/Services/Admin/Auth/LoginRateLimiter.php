<?php

namespace App\Services\Admin\Auth;

use App\Traits\Response\ValidationResponseTrait;
use Illuminate\Support\Facades\RateLimiter;

class LoginRateLimiter
{
    use ValidationResponseTrait;

    /**
     * Check if the user has too many failed login attempts.
     *
     * @param string $key
     * @return void
     */
    public function checkTooManyFailedAttempts(string $key): void
    {
        // Check if the user has too many failed login attempts
        if (RateLimiter::tooManyAttempts($key, env('LOGIN_ATTEMPTS_LIMIT' , 10))) {
            // Respond with validation errors if the user is rate limited
            $this->respondValidationErrors([
                'email' => [__('admin/auth.too_many_attempts')],
            ]);
        }
    }

    /**
     * Clear the rate limiter for the given key.
     *
     * @param string $key
     * @return void
     */
    public function increment(string $key): void
    {
        // Increment the rate limiter for the given key
        RateLimiter::hit($key);
    }

    /**
     * Clear the rate limiter for the given key.
     *
     * @param string $key
     * @return void
     */
    public function clear(string $key): void
    {
        // Clear the rate limiter for the given key
        RateLimiter::clear($key);
    }

    /**
     * Generate a unique throttle key for the given value and IP address.
     *
     * @param string $value
     * @param string $ip
     * @return string
     */
    public function throttleKey(string $value, string $ip): string
    {
        // Generate a unique throttle key using the value and IP address
        return $value . '|' . $ip;
    }
}
