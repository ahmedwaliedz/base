<?php
namespace App\Models;

use App\Traits\Models\BaseModelTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory, Translatable, BaseModelTrait;

    public const RELATIONS = ['translations', 'parent', 'children'];

    protected $fillable = ['slug', 'icon', 'is_active', 'parent_id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $attributes = [
        'is_active' => true,
    ];

    public $translatedAttributes = ['name'];

    public function parent() {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(self::class, 'parent_id');
    }

}
