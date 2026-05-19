<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneralTrait;

class Slider extends Model
{
    use HasFactory, Translatable, GeneralTrait;

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'link', 'is_active', 'type'];

    public $translatedAttributes = ['title', 'description'];

    protected $attributes = [
        'is_active'  => true,
    ];

}
