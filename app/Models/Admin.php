<?php

namespace App\Models;

use App\Enums\AdminType;
use App\Enums\ModelNotificationType;
use App\Traits\Models\BaseAuthModelTrait;
use App\Traits\Models\CanRetrieve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable /* implements HasMedia */
{
    use BaseAuthModelTrait, CanRetrieve, HasFactory, SoftDeletes/* , HasMediaLibrary */;

    /**
     * Available notification types for admins
     */
    protected static array $availableNotificationTypes = [
        ModelNotificationType::BLOCKED,
        ModelNotificationType::NOTBLOCKED,
    ];

    protected const UPLOAD_DIRECTORY = 'admins';

    protected const FILES = [
        'image',
    ];

    public const RELATIONS = [
        'role',
    ];

    /**
     * Export columns schema to be used for CSV/Excel/Print/PDF.
     * Labels are translation keys and will be translated at runtime.
     */
    public const EXPORT_COLUMNS = [
        ['key' => 'id', 'label' => 'admin/main.id'],
        ['key' => 'name', 'label' => 'admin/main.name'],
        ['key' => 'email', 'label' => 'admin/main.email'],
        ['key' => 'full_phone', 'label' => 'admin/main.full_phone'],
        ['key' => 'country_code', 'label' => 'admin/main.country_code'],
        ['key' => 'role_name', 'label' => 'admin/main.role'],
        ['key' => 'type_label', 'label' => 'admin/main.type'],
        ['key' => 'notify_label', 'label' => 'admin/main.is_notify'],
        ['key' => 'deleted_at', 'label' => 'admin/main.deleted_at'],
        ['key' => 'status_label', 'label' => 'admin/main.status'],
    ];

    protected $fillable = [
        'name',
        'phone',
        'country_code',
        'full_phone',
        'email',
        'password',
        'is_blocked',
        'is_notify',
        'type',
        'role_id',
        'image',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'type' => AdminType::class,
        'is_blocked' => 'boolean',
        'is_notify' => 'boolean',
    ];

    protected $attributes = [
        'is_blocked' => false,
        'is_notify' => true,
        'image' => 'default.png',
    ];

    public function scopeStatus($query, $status)
    {
        if ($status === 'active') {
            return $query->where('is_blocked', false);
        } elseif ($status === 'blocked') {
            return $query->where('is_blocked', true);
        }

        return $query;
    }

    public function getRoleNameAttribute($value)
    {
        return $this->role_id ? $this->role?->name : __('admin/main.super_admin');
    }

    /** Plain label for listings and exports. */
    public function getTypeLabelAttribute(): ?string
    {
        return $this->type?->label();
    }

    /** Localized yes/no for listings and exports. */
    public function getNotifyLabelAttribute(): string
    {
        return $this->is_notify ? __('admin/main.yes') : __('admin/main.no');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // no-op: admins table has no remember_token column
    }

    /**
     * Get available notification types for admins
     */
    public static function getAvailableNotificationTypes(): array
    {
        return self::$availableNotificationTypes;
    }
}
