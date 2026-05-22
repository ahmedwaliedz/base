<?php

namespace App\Models;

use App\Enums\FaqType;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;

class Faq extends Model
{
    use HasFactory, Translatable, GeneralTrait, FilterableTrait;

    public const RELATIONS = ['translations'];

    protected $fillable = ['type', 'is_active'];

    public $translatedAttributes = ['question', 'answer'];

    protected $casts = [
        'type'      => FaqType::class,
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

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
