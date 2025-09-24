<?php
namespace App\Models;

use App\Traits\GeneralTrait;
use App\Traits\Models\BaseFileWithTranslations;
use App\Traits\Models\BaseModelTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model {
    use HasFactory, BaseModelTrait ,BaseFileWithTranslations ,GeneralTrait;

    protected $fillable = [
        'code',
        'flag',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public $translatedAttributes = ['name'];

    public function regions() {
        return $this->hasMany(Region::class);
    }

    public function cities() {
        return $this->hasManyThrough(City::class, Region::class);
    }

}
