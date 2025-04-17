<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['city_id','is_active'];

    public $translatedAttributes = ['name'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active'  => true,
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

}
