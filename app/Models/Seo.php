<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use GeneralTrait, HasFactory, Translatable, FilterableTrait;

    public const PATH_NAME = 'seo';

    public const RELATIONS = ['translations', 'seoable'];

    protected $fillable = ['image'];
    public $translatedAttributes = ['meta_title', 'meta_description', 'meta_keywords'];

    public function seoable()
    {
        return $this->morphTo();
    }

}
