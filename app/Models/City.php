<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory, Translatable;

    public const RELATIONS = ['translations', 'country', 'region', 'districts'];

    protected $fillable = ['country_id', 'region_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active'  => true,
    ];

    public $translatedAttributes = ['name'];



    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }


}
