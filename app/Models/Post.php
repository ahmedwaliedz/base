<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;

class Post extends Model
{
    use HasFactory, Translatable, GeneralTrait, FilterableTrait;

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'is_active'];

    public $translatedAttributes = ['title', 'content'];
}
