<?php
namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Models\HasConfiguredTranslations;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    use FilterableTrait, HasFactory, Translatable, GeneralTrait, HasConfiguredTranslations;

    public const RELATIONS = ['admins', 'permissions', 'translations'];

    protected $fillable = ['ar', 'en'];

    public $translatedAttributes = ['name'];

    public function admins() {
        return $this->hasMany(Admin::class);
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function scopeName($query, $value) {
        return $query->whereTranslationLike('name', '%' . $value . '%');
    }
}
