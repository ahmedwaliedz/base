<?php

namespace App\Models;

use App\Enums\PageType;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['slug', 'icon', 'type'];

    public $translatedAttributes = ['title', 'content'];

    public function setSlugAttribute(string $value): void
    {
        $this->attributes['slug'] = strtolower(preg_replace('/\s+/', '-', $value));
    }

    protected $casts = [
        'type' => PageType::class,
    ];
}
