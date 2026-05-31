<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Models\HasConfiguredTranslations;
use App\Traits\Models\InteractsWithFilesAndTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use InteractsWithFilesAndTranslations, FilterableTrait, GeneralTrait, HasFactory, SoftDeletes, HasConfiguredTranslations;

    public const RELATIONS = ['translations'];

    protected $fillable = ['image', 'is_active'];

    public $translatedAttributes = ['title', 'content'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    protected const UPLOAD_DIRECTORY = 'posts';

    protected const FILES = [
        'image',
    ];

    public function scopeTitle($query, $value)
    {
        return $query->whereTranslationLike('title', '%'.$value.'%');
    }

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
