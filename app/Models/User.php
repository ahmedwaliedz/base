<?php

namespace App\Models;

use App\Traits\GeneralTrait;
use App\Traits\HandleNumbersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable , SoftDeletes , GeneralTrait , HandleNumbersTrait;

    protected $fillable = [
        'name',
        'image',
        'lang2',
        'email',
        'phone',
        'country_code',
        'full_phone',
        'password',
        'is_blocked',
        'is_notify',
        'is_active',
        'is_completed',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_blocked'        => 'boolean',
        'is_notify'         => 'boolean',
        'is_active'         => 'boolean',
        'is_completed'      => 'boolean',
    ];

    protected $attributes = [
        'is_blocked'        => false,
        'is_notify'         => true,
        'is_active'         => true,
        'is_completed'      => true,
        'image'             => 'default.png',
        'lang2'              => 'ar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setFullPhoneAttribute()
    {
        $this->attributes['full_phone'] = $this->fixPhone($this->attributes['country_code'])  . $this->fixPhone($this->attributes['phone']);
    }



}
