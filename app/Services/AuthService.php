<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use App\Contracts\UserRepositoryInterface;
use App\Contracts\CodeSenderInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\CarbonImmutable;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CodeSenderInterface $codeSender
    ) {}

    public function loginWithPassword(array $validatedData): array
    {
        $phone = $validatedData['phone'];
        $password = $validatedData['password'];
        
        $user = $this->userRepository->findByPhone($phone);
        
        if (!$user || !$user->is_active) {
            throw new AuthenticationException(__('responses.invalid_credentials'));
        }
        
        if (!Hash::check($password, $user->password)) {
            throw new AuthenticationException(__('responses.invalid_credentials'));
        }
        
        $token = $user->createToken('api')->plainTextToken;
        
        Log::info('User logged in with password', [
            'user_id' => $user->id,
            'phone' => $phone,
        ]);
        
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function loginWithCode(array $validatedData): array
    {
        $phone = $validatedData['phone'];
        $code = $validatedData['code'];
        
        $user = $this->userRepository->findByPhone($phone);
        
        if (!$user || !$user->is_active) {
            throw new AuthenticationException(__('responses.invalid_credentials'));
        }
        
        // Check if code exists and is not expired
        if (!$user->activation_code || !$user->activation_expires_at) {
            throw new AuthenticationException(__('responses.code_not_found'));
        }
        
        if (CarbonImmutable::now()->isAfter($user->activation_expires_at)) {
            throw new AuthenticationException(__('responses.code_expired'));
        }
        
        // Check max attempts
        if ($user->activation_attempts >= config('auth_codes.max_attempts')) {
            throw new AuthenticationException(__('responses.too_many_attempts'));
        }
        
        // Verify code
        if (!Hash::check($code, $user->activation_code)) {
            $this->userRepository->incrementActivationAttempts($user);
            throw new AuthenticationException(__('responses.code_invalid'));
        }
        
        // Reset attempts and clear code on successful login
        $this->userRepository->resetActivationAttempts($user);
        $user->update([
            'activation_code' => null,
            'activation_expires_at' => null,
        ]);
        
        $token = $user->createToken('api')->plainTextToken;
        
        Log::info('User logged in with activation code', [
            'user_id' => $user->id,
            'phone' => $phone,
        ]);
        
        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function requestActivationCode(string $phone): void
    {
        $user = $this->userRepository->findByPhone($phone);
        
        // Check cooldown period
        if ($user && $user->last_activation_requested_at) {
            $cooldownSeconds = config('auth_codes.resend_cooldown_seconds');
            $lastRequest = CarbonImmutable::parse($user->last_activation_requested_at);
            
            if (CarbonImmutable::now()->diffInSeconds($lastRequest) < $cooldownSeconds) {
                $remainingSeconds = $cooldownSeconds - CarbonImmutable::now()->diffInSeconds($lastRequest);
                throw new AuthenticationException(__('responses.wait_for_new_code', ['seconds' => $remainingSeconds]));
            }
        }
        
        // Generate new code
        $code = $this->generateActivationCode();
        $expiresAt = CarbonImmutable::now()->addMinutes(config('auth_codes.ttl_minutes'));
        
        if ($user) {
            // Update existing user
            $this->userRepository->updateActivationCode($user, $code, $expiresAt);
            $this->userRepository->updateLastActivationRequest($user);
        } else {
            // Create new user
            $user = $this->userRepository->create([
                'phone' => $phone,
                'phone_normalized' => $phone,
                'activation_code' => Hash::make($code),
                'activation_expires_at' => $expiresAt,
                'last_activation_requested_at' => CarbonImmutable::now(),
                'is_active' => true,
                'profile_completed' => false,
            ]);
        }
        
        // Send code
        $this->codeSender->sendCode($phone, $code);
        
        Log::info('Activation code requested', [
            'phone' => $phone,
            'user_id' => $user->id,
        ]);
    }

    public function logout(User $user, ?string $tokenId = null): void
    {
        if ($tokenId) {
            $user->tokens()->where('id', $tokenId)->delete();
        } else {
            $user->currentAccessToken()->delete();
        }
        
        Log::info('User logged out', [
            'user_id' => $user->id,
        ]);
    }

    private function generateActivationCode(): string
    {
        $length = config('auth_codes.length', 6);
        return str_pad((string) random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
