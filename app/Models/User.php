<?php
namespace App\Models;

use App\Enums\ModelNotificationType;
use App\Traits\Models\BaseAuthModelTrait;
use App\Traits\Models\CanRetrieve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use BaseAuthModelTrait, HasFactory, SoftDeletes, CanRetrieve;

    /**
     * Available notification types for users
     *
     * @var array
     */
    protected static array $availableNotificationTypes = [
        ModelNotificationType::ACTIVE,
    ];

    protected $fillable = [
        'name',
        'image',
        'lang2',
        'email',
        'phone',
        'phone_normalized',
        'activation_code',
        'activation_expires_at',
        'activation_attempts',
        'last_activation_requested_at',
        'country_code',
        'full_phone',
        'password',
        'is_blocked',
        'is_notify',
        'is_active',
        'is_complete_info',
        'is_completed',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'activation_expires_at' => 'datetime',
        'last_activation_requested_at' => 'datetime',
        'password'          => 'hashed',
        'is_blocked'        => 'boolean',
        'is_notify'         => 'boolean',
        'is_active'         => 'boolean',
        'is_complete_info'  => 'boolean',
        'is_completed'      => 'boolean',
    ];

    protected $attributes = [
        'is_blocked'   => false,
        'is_notify'    => true,
        'is_active'    => true,
        'is_completed' => true,
        'image'        => 'default.png',
        'lang2'        => 'ar',
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

    public function setFullPhoneAttribute() {
        $this->attributes['full_phone'] = $this->fixPhone($this->attributes['country_code']) . $this->fixPhone($this->attributes['phone']);
    }

    public function getFullPhoneAttribute() {
        if (! empty($this->full_phone)) {
            return $this->full_phone;
        }
        return $this->attributes['country_code'] . $this->attributes['phone'];
    }

    /**
     * Get available notification types for users
     *
     * @return array
     */
    public static function getAvailableNotificationTypes(): array {
        return self::$availableNotificationTypes;
    }

}
