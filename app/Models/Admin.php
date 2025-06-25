<?php

namespace App\Models;

use App\Enums\AdminType;
use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\HandleNumbersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, GeneralTrait, HandleNumbersTrait, FilterableTrait;



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
        'password' => 'hashed',
        'type' => AdminType::class,
    ];

    protected $attributes = [
        'is_blocked'        => false,
        'is_notify'         => true,
        'image'             => 'default.png',
    ];

    public function getImageUrlAttribute($value)
    {
        return asset('uploads/admins/' . $this->attributes['image']);
    }

    public function getRoleNameAttribute($value)
    {
        return $this->role_id ? $this->role->name : __('admin/main.super_admin');
    }

    public function setFullPhoneAttribute(string $value): void
    {
        $this->attributes['full_phone'] = $this->attributes['country_code'] .$this->attributes['phone'] ;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function statusData(){
        return [
                'label' => $this->is_blocked ? __('admin/main.blocked') : __('admin/main.active'),
                'class' => $this->is_blocked ? 'bg-label-warning' : 'bg-label-success',
        ];
    }

    public function scopeStatus($query, $status)
    {
        if ($status === 'active') {
            return $query->where('is_blocked', false);
        } elseif ($status === 'blocked') {
            return $query->where('is_blocked', true);
        }
        return $query;
    }

}
