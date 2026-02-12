<?php

namespace App\Services\Auth;

use App\Support\PhoneNormalizer;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function loginWithPassword(array $validatedData): array
    {
        $credentials = filter_var($validatedData['login_value'], FILTER_VALIDATE_EMAIL)
            ? ['email' => $validatedData['login_value'], 'password' => $validatedData['password']]
            : ['phone' => PhoneNormalizer::normalize($validatedData['login_value']), 'country_code' => PhoneNormalizer::normalize($validatedData['country_code']), 'password' => $validatedData['password']];
        if (!Auth::attempt($credentials)) {
            return [ 'status'  => 'validationError', 'message' => __('api/auth.invalid_credentials')];
        }
        return match (true) {
            !Auth::user()->is_active    => [ 'status'  => 'notActive', 'message' => __('api/auth.account_not_active'), 'user' => Auth::user()],
            Auth::user()->is_blocked    => [ 'status'  => 'blocked', 'message' => __('api/auth.account_blocked')],
            default                     => [ 'status' => 'success', 'user'   => Auth::user(), 'token'  => Auth::user()->createToken('authToken')->plainTextToken],
        };
    }
}
