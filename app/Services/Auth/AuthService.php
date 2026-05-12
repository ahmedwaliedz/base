<?php

namespace App\Services\Auth;

use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Contracts\AuthServiceInterface;
use App\Contracts\CodeSenderInterface;
use App\Models\User;
use App\Services\Otp\OtpService;
use App\Support\PhoneNormalizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    private OtpService $otpService;

    public function __construct(
        private readonly CodeSenderInterface $codeSender,
        ?OtpService $otpService = null
    ) {
        $this->otpService = $otpService ?? new OtpService($this->codeSender);
    }

    public function loginWithPassword(array $validatedData): array
    {
        $isEmail = filter_var($validatedData['login_value'], FILTER_VALIDATE_EMAIL);
        $credentials = $isEmail
            ? ['email' => $validatedData['login_value'], 'password' => $validatedData['password']]
            : ['phone' => PhoneNormalizer::normalize($validatedData['login_value']), 'country_code' => PhoneNormalizer::normalize($validatedData['country_code']), 'password' => $validatedData['password']];

        if (!Auth::attempt($credentials)) {
            return [ 'status'  => 'failed', 'message' => __('api/auth.invalid_credentials')];
        }
        return match (true) {
            !Auth::user()->is_active    => [ 'status'  => 'notActive', 'message' => __('api/auth.account_not_active'), 'user' => Auth::user()],
            Auth::user()->is_blocked    => [ 'status'  => 'blocked', 'message' => __('api/auth.account_blocked')],
            default                     => [ 'status' => 'success', 'user'   => Auth::user(), 'token'  => Auth::user()->createToken('authToken')->plainTextToken],
        };
    }

    public function loginWithCode(array $validatedData): array
    {
        $phone = ltrim($validatedData['phone'], '+');
        $countryCode = ltrim($validatedData['country_code'] ?? '', '+');
        $code = $validatedData['code'];

        $user = User::where('phone', $phone)
            ->where('country_code', $countryCode)
            ->first();

        if (!$user) {
            $phoneNormalized = $countryCode . $phone;
            $user = User::where('phone_normalized', $phoneNormalized)->first();
        }

        if (!$user) {
            throw new \Illuminate\Auth\AuthenticationException('Invalid credentials.');
        }

        $otp = $user->otps()
            ->where('type', OtpType::ACTIVATE)
            ->where('status', OtpStatus::ACTIVE)
            ->latest()
            ->first();

        if (!$otp) {
            throw new \Illuminate\Auth\AuthenticationException('No valid activation code found.');
        }

        if ($otp->isExpired()) {
            $otp->markAsFailed();
            throw new \Illuminate\Auth\AuthenticationException('Activation code has expired.');
        }

        if ($otp->tries >= config('auth_codes.max_attempts', 5)) {
            $otp->markAsFailed();
            throw new \Illuminate\Auth\AuthenticationException('Too many failed attempts. Please request a new code.');
        }

        if ($otp->verification_code !== $code) {
            $otp->incrementTries();
            throw new \Illuminate\Auth\AuthenticationException('Invalid activation code.');
        }

        $otp->markAsFinished();
        $user->update(['is_active' => true]);

        return [
            'user' => $user->fresh(),
            'token' => $user->createToken('authToken')->plainTextToken,
        ];
    }

    public function requestActivationCode(string $phone, string $countryCode): void
    {
        $normalizedPhone = ltrim($phone, '+');
        $normalizedCountryCode = ltrim($countryCode, '+');

        DB::transaction(function () use ($normalizedPhone, $normalizedCountryCode): void {
            $user = User::where('phone', $normalizedPhone)
                ->where('country_code', $normalizedCountryCode)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => 'New User',
                    'phone' => $normalizedPhone,
                    'country_code' => $normalizedCountryCode,
                    'phone_normalized' => $normalizedCountryCode . $normalizedPhone,
                    'email' => $normalizedPhone . '_' . $normalizedCountryCode . '@local.com',
                    'is_active' => false,
                    'is_complete_info' => false,
                ]);
            }

            $lastOtp = $user->otps()
                ->where('type', OtpType::ACTIVATE)
                ->latest()
                ->lockForUpdate()
                ->first();

            if ($lastOtp && $lastOtp->status === OtpStatus::ACTIVE) {
                $cooldownSeconds = config('auth_codes.resend_cooldown_seconds', 60);
                if (!$lastOtp->created_at->addSeconds($cooldownSeconds)->isPast()) {
                    throw new \Illuminate\Http\Exceptions\ThrottleRequestsException(
                        'Too many requests. Please wait before requesting a new code.'
                    );
                }
            }

            $this->otpService->sendOtp($user, OtpType::ACTIVATE);
        });
    }

    public function logout(User $user, ?string $tokenId = null): void
    {
        if ($tokenId) {
            $user->tokens()->where('id', $tokenId)->delete();
        } else {
            $user->tokens()->delete();
        }
    }
}
