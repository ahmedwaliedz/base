<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;

class IntroPage extends Model
{
    use HasFactory, GeneralTrait, Translatable, FilterableTrait;

    public const PATH_NAME = 'intro-pages';

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
