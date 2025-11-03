<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Activation Code Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for phone-based activation codes used in authentication
    |
    */

    // Length of activation code (number of digits)
    'length' => env('AUTH_CODE_LENGTH', 6),

    // Time to live in minutes
    'ttl_minutes' => env('AUTH_CODE_TTL_MINUTES', 10),

    // Maximum number of attempts before lockout
    'max_attempts' => env('AUTH_CODE_MAX_ATTEMPTS', 5),

    // Cooldown period in seconds before requesting new code
    'resend_cooldown_seconds' => env('AUTH_CODE_RESEND_COOLDOWN', 60),

    // Required fields for profile completion
    'required_profile_fields' => [
        'name',
        // Add more fields as needed: 'email', 'date_of_birth', etc.
    ],

    // Rate limiting for request-code endpoint
    'rate_limit' => [
        'max_attempts' => 3,
        'decay_minutes' => 1,
    ],
];
