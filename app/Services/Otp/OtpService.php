<?php

declare(strict_types=1);

namespace App\Services\Otp;

use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Models\Otp;
use Illuminate\Database\Eloquent\Model;

class OtpService
{
    /**
     * Send OTP to the given model.
     */
    public function sendOtp(Model $otpable, OtpType $type, ?string $changedValue = null , ?string $countryCode = null): Otp
    {
        // Invalidate previous OTPs of the same type
        $this->invalidatePreviousOtps($otpable, $type);
        // Generate code
        $code = $this->generateCode();
        // Create OTP record
        return $otpable->otps()->create([
            'type'                          => $type,
            'changed_value'                 => $changedValue ?? null,
            'country_code'                  => $countryCode ?? null,
            'verification_code'             => $code,
            'verification_code_expire_at'   => now()->addMinutes(config('auth_codes.ttl_minutes', 10)),
            'status'                        => OtpStatus::ACTIVE,
        ]);
    }

    /**
     * Verify OTP code for the given model.
     */
    public function verifyOtp(Model $otpable, string $code, OtpType $type): bool
    {
        $otp = $this->getActiveOtp($otpable, $type);

        if (!$otp) {
            return false;
        }

        // Check if expired
        if ($otp->isExpired()) {
            $otp->markAsFailed();
            return false;
        }

        // Check max attempts
        if ($otp->tries >= config('auth_codes.max_attempts', 5)) {
            $otp->markAsFailed();
            return false;
        }

        // Verify code
        if ($otp->verification_code !== $code) {
            $otp->incrementTries();
            return false;
        }

        // Mark as finished
        $otp->markAsFinished();

        return true;
    }

    /**
     * Check if user can request a new OTP (cooldown check).
     */
    public function canResend(Model $otpable, OtpType $type): bool
    {
        $lastOtp = $otpable->otps()
            ->where('type', $type)
            ->latest()
            ->first();

        if (!$lastOtp) {
            return true;
        }

        $cooldownSeconds = config('auth_codes.resend_cooldown_seconds', 60);

        return $lastOtp->created_at->addSeconds($cooldownSeconds)->isPast();
    }

    /**
     * Get the active OTP for the given model and type.
     */
    public function getActiveOtp(Model $otpable, OtpType $type): ?Otp
    {
        return $otpable->otps()
            ->where('type', $type)
            ->where('status', OtpStatus::ACTIVE)
            ->latest()
            ->first();
    }

    /**
     * Generate a random OTP code.
     */
    private function generateCode(): string
    {
        return (string) 1234;
        // $length = config('auth_codes.length', 4);
        // return str_pad((string) random_int(0, (10 ** x   $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Invalidate all previous active OTPs of the same type.
     */
    private function invalidatePreviousOtps(Model $otpable, OtpType $type): void
    {
        $otpable->otps()
            ->where('type', $type)
            ->where('status', OtpStatus::ACTIVE)
            ->update(['status' => OtpStatus::FINISHED]);
    }
}
