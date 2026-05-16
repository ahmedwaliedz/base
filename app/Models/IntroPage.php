<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class IntroPage extends Model
{
    use  Translatable ;

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'link', 'is_active'];

    public $translatedAttributes = ['title', 'description'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active'  => true,
    ];
}
