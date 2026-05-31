<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Models\HasConfiguredTranslations;
use App\Traits\Models\InteractsWithFilesAndTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntroPage extends Model
{
    use HasFactory, GeneralTrait, InteractsWithFilesAndTranslations, FilterableTrait, HasConfiguredTranslations;

    public const PATH_NAME = 'intro-pages';

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'link', 'is_active'];

    public $translatedAttributes = ['title', 'description'];

    protected const UPLOAD_DIRECTORY = 'intro-pages';

    protected const FILES = [
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active'  => true,
    ];
}
