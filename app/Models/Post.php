<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Translatable;

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'is_active'];

    public $translatedAttributes = ['title', 'content'];
}
