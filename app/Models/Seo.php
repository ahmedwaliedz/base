<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory , Translatable;

    public const RELATIONS = ['translations', 'seoable'];

    protected $fillable = ['image'];
    public $translatedAttributes = ['meta_title', 'meta_description', 'meta_keywords'];

    public function seoable()
    {
        return $this->morphTo();
    }

}
