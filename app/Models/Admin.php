<?php
namespace App\Models;

use App\Enums\AdminType;
use App\Enums\ModelNotificationType;
use App\Traits\Models\BaseAuthModelTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Admin extends Authenticatable implements HasMedia {

    use BaseAuthModelTrait;

    /**
     * Available notification types for admins
     *
     * @var array
     */
    protected static array $availableNotificationTypes = [
        ModelNotificationType::BLOCKED,
        ModelNotificationType::NOTBLOCKED,
    ];

    protected const FILES = [
        'image',
    ];

    protected const UPLOAD_DIRECTORY  = 'admins';
    protected const UPLOAD_COLLECTION = 'admins';
    protected const UPLOAD_TYPE       = 'custom'; // or 'media-library' based on your implementation

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
        'password'   => 'hashed',
        'type'       => AdminType::class,
        'is_blocked' => 'boolean',
        'is_notify'  => 'boolean',
    ];

    protected $attributes = [
        'is_blocked' => false,

        'is_notify'  => true,
        'image'      => 'default.png',
    ];


    public function registerMediaConversions(?Media $media = null): void {
        $this->registerImageConversions($media);
    }

    public function getRoleNameAttribute($value) {
        return $this->role_id ? $this->role->name : __('admin/main.super_admin');
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function statusData() {
        return [
            'label' => $this->is_blocked ? __('admin/main.blocked') : __('admin/main.active'),
            'class' => $this->is_blocked ? 'bg-label-warning' : 'bg-label-success',
        ];
    }

    /**
     * Get available notification types for admins
     *
     * @return array
     */
    public static function getAvailableNotificationTypes(): array {
        return self::$availableNotificationTypes;
    }
}
