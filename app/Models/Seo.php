<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Models\HasConfiguredTranslations;
use App\Traits\Models\InteractsWithFilesAndTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use GeneralTrait, HasFactory, InteractsWithFilesAndTranslations, FilterableTrait, HasConfiguredTranslations;

    public const PATH_NAME = 'seo';

    public const RELATIONS = ['translations', 'seoable'];

    protected $fillable = ['image'];
    public $translatedAttributes = ['meta_title', 'meta_description', 'meta_keywords'];

    protected const UPLOAD_DIRECTORY = 'seo';

    protected const FILES = [
        'image',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }

}
