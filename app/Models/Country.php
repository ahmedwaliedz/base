<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'code',
        'flag',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active'  => true,
    ];

    public $translatedAttributes = ['name'];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, Region::class);
    }



}
