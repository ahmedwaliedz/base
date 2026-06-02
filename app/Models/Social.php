<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use GeneralTrait, FilterableTrait, BaseFilesTrait;

    public const RELATIONS = [];

    protected const UPLOAD_DIRECTORY = 'socials';

    protected const FILES = ['image'];

    protected $fillable = ['image', 'link', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active'  => true,
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

}
