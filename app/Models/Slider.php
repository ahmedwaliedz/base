<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Models\HasConfiguredTranslations;
use App\Traits\Models\InteractsWithFilesAndTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory, InteractsWithFilesAndTranslations, GeneralTrait, FilterableTrait, HasConfiguredTranslations;

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'link', 'is_active', 'type'];

    public $translatedAttributes = ['title', 'description'];

    protected const UPLOAD_DIRECTORY = 'sliders';

    protected const FILES = [
        'image',
    ];

    protected $attributes = [
        'is_active'  => true,
    ];

}
