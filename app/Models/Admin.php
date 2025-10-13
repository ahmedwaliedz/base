<?php
namespace App\Models;

use App\Enums\AdminType;
use App\Enums\ModelNotificationType;
use App\Traits\Models\BaseAuthModelTrait;
use App\Traits\Models\CanRetrieve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable/* implements HasMedia */{

    use BaseAuthModelTrait, HasFactory, SoftDeletes, CanRetrieve/* , HasMediaLibrary */;

    /**
     * Available notification types for admins
     *
     * @var array
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
        ['key' => 'role_name', 'label' => 'admin/main.role'],
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
        'type'       => AdminType::class,
        'is_blocked' => 'boolean',
        'is_notify'  => 'boolean',
    ];

    protected $attributes = [
        'is_blocked' => false,
        'is_notify'  => true,
        'image'      => 'default.png',
    ];

    public function scopeStatus($query, $status) {
        if ($status === 'active') {
            return $query->where('is_blocked', false);
        } elseif ($status === 'blocked') {
            return $query->where('is_blocked', true);
        }
        return $query;
    }

    public function getRoleNameAttribute($value) {
        return $this->role_id ? $this->role?->name : __('admin/main.super_admin');
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

    // Accessor used by export to get a plain status label
    public function getStatusLabelAttribute() {
        return $this->statusData()['label'] ?? null;
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
