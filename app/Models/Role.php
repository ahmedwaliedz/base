<?php
namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\Models\BaseFileWithTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    use FilterableTrait, HasFactory, BaseFileWithTranslations;

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
