<?php

namespace App\Models;

use App\Enums\FaqType;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory, Translatable;

    public const RELATIONS = ['translations'];

    protected $fillable = ['slug', 'icon', 'type'];

    public $translatedAttributes = ['question', 'answer'];

    protected $casts = [
        'type' => FaqType::class,
    ];
}
