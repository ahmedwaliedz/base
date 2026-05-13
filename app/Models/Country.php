<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Models\BaseFileWithTranslations;
use App\Traits\Models\BaseModelTrait;
use App\Traits\Models\CanRetrieve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Country extends Model implements HasMedia
{
    use BaseFileWithTranslations, BaseModelTrait ,CanRetrieve ,FilterableTrait ,GeneralTrait , HasFactory, SoftDeletes;

    public const RELATIONS = [];

    /**
     * Export columns schema for admin CSV/Excel/Print/PDF.
     */
    public const EXPORT_COLUMNS = [
        ['key' => 'id', 'label' => 'admin/main.id'],
        ['key' => 'code', 'label' => 'admin/main.code'],
        ['key' => 'name', 'label' => 'admin/main.name'],
        ['key' => 'is_active', 'label' => 'admin/main.is_active'],
        ['key' => 'flag', 'label' => 'admin/main.flag'],
        ['key' => 'created_at', 'label' => 'admin/main.created_at'],
        ['key' => 'updated_at', 'label' => 'admin/main.updated_at'],
    ];

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
        'flag' => 'default.png',
    ];

    protected const UPLOAD_DIRECTORY = 'countries';

    protected const FILES = [
        'flag',
    ];

    public $translatedAttributes = ['name'];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function cities()
    {
        return $this->hasManyThrough(City::class, Region::class);
    }

    public function scopeName($query, $value)
    {
        return $query->whereTranslationLike('name', '%'.$value.'%');
    }

    /**
     * Boolean / enum-style filters must not use LIKE (see FilterableTrait::applyColumnFilter).
     */
    protected function applyColumnFilter($query, $column, $value): void
    {
        if ($column === 'is_active') {
            if ($value === 'active_only') {
                $query->where('is_active', true);
            } elseif ($value === 'inactive_only') {
                $query->where('is_active', false);
            }

            return;
        }

        $query->where($column, 'like', '%'.$value.'%');
    }
}
