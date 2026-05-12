<?php

namespace App\Models;

use App\Enums\OtpStatus;
use App\Enums\OtpType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Otp extends Model
{
    protected $fillable = [
        'otpable_id',
        'otpable_type',
        'changed_value',
        'country_code',
        'verification_code',
        'verification_code_expire_at',
        'type',
        'status',
        'tries',
    ];

    protected $casts = [
        'type'                          => OtpType::class,
        'status'                        => OtpStatus::class,
        'verification_code_expire_at'   => 'datetime',
    ];

    protected $attributes = [
        'tries'     => 0,
        'status'    => OtpStatus::ACTIVE,
    ];

    /**
     * Get the parent otpable model (User, Admin, etc.).
     */
    public function otpable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if the OTP has expired.
     */
    public function isExpired(): bool
    {
        return $this->verification_code_expire_at && $this->verification_code_expire_at->isPast();
    }

    /**
     * Increment the number of tries.
     */
    public function incrementTries(): self
    {
        $this->increment('tries');
        return $this;
    }

    /**
     * Mark the OTP as finished.
     */
    public function markAsFinished(): self
    {
        $this->update(['status' => OtpStatus::FINISHED]);
        return $this;
    }

    /**
     * Mark the OTP as failed.
     */
    public function markAsFailed(): self
    {
        $this->update(['status' => OtpStatus::FAILED]);
        return $this;
    }

    /**
     * Check if the OTP is still active.
     */
    public function isActive(): bool
    {
        return $this->status === OtpStatus::ACTIVE;
    }

    /**
     * Check if the OTP is valid (active and not expired).
     */
    public function isValid(): bool
    {
        return $this->isActive() && !$this->isExpired();
    }
}
