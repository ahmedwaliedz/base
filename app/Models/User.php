<?php

namespace App\Models;

use App\Enums\ModelNotificationType;
use App\Traits\Models\BaseAuthModelTrait;
use App\Traits\Models\CanRetrieve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use BaseAuthModelTrait, CanRetrieve, HasApiTokens, HasFactory, SoftDeletes;

    /**
     * Available notification types for users
     */
    protected static array $availableNotificationTypes = [
        ModelNotificationType::ACTIVE,
    ];

    protected $fillable = [
        'name',
        'image',
        'phone',
        'phone_normalized',
        'last_activation_requested_at',
        'country_code',
        'password',
        'is_blocked',
        'is_notify',
        'is_active',
        'is_complete_info',
        'email_verified_at',
        'phone_verified_at',
        'email',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_activation_requested_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean',
        'is_notify' => 'boolean',
        'is_active' => 'boolean',
        'is_complete_info' => 'boolean',
    ];

    protected $attributes = [
        'is_blocked' => false,
        'is_notify' => true,
        'is_active' => true,
        'is_complete_info' => true,
        'image' => 'default.png',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected const UPLOAD_DIRECTORY = 'users';

    protected const FILES = [
        'image',
    ];

    public const RELATIONS = [];

    /**
     * Export columns schema to be used for CSV/Excel/Print/PDF.
     * Labels are translation keys and will be translated at runtime.
     */
    public const EXPORT_COLUMNS = [
        ['key' => 'id', 'label' => 'admin/main.id'],
        ['key' => 'name', 'label' => 'admin/main.name'],
        ['key' => 'phone', 'label' => 'admin/main.phone'],
        ['key' => 'email', 'label' => 'admin/main.email'],
        ['key' => 'full_phone', 'label' => 'admin/main.full_phone'],
        ['key' => 'is_blocked', 'label' => 'admin/main.is_blocked'],
        ['key' => 'is_notify', 'label' => 'admin/main.is_notify'],
        ['key' => 'is_active', 'label' => 'admin/main.is_active'],
        ['key' => 'is_completed', 'label' => 'admin/main.is_completed'],
        ['key' => 'email_verified_at', 'label' => 'admin/main.email_verified_at'],
        ['key' => 'phone_verified_at', 'label' => 'admin/main.phone_verified_at'],
    ];

    public function setPhoneNormalizedAttribute()
    {
        $this->attributes['phone_normalized'] = $this->fixPhone($this->attributes['country_code']).$this->fixPhone($this->attributes['phone']);
    }

    /**
     * Get available notification types for users
     */
    public static function getAvailableNotificationTypes(): array
    {
        return self::$availableNotificationTypes;
    }

    /**
     * Get the OTPs for the user.
     */
    public function otps()
    {
        return $this->morphMany(Otp::class, 'otpable');
    }

    /**
     * Complaints submitted by this user (morph: complaiantable).
     */
    public function complaints(): MorphMany
    {
        return $this->morphMany(Complaint::class, 'complaiantable');
    }

    /**
     * Contact messages submitted by this user (morph: contactable).
     */
    public function contactMessages(): MorphMany
    {
        return $this->morphMany(ContactMessage::class, 'contactable');
    }

    /**
     * Boolean column is_blocked must use equality, not LIKE (see FilterableTrait::applyColumnFilter).
     * Option values are non-empty strings so FilterHelpers::shouldApplyFilter accepts them (PHP empty('0') is true).
     */
    protected function applyColumnFilter($query, $column, $value): void
    {
        if ($column === 'is_blocked') {
            if ($value === 'blocked_only') {
                $query->where('is_blocked', true);
            } elseif ($value === 'not_blocked') {
                $query->where('is_blocked', false);
            }

            return;
        }

        $query->where($column, 'like', '%'.$value.'%');
    }
}
