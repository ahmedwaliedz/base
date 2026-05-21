<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use GeneralTrait, HasFactory, FilterableTrait, SoftDeletes;

    public const RELATIONS = ['contactable', 'replays'];

    public const PATH_NAME = 'contact-messages';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'is_read',
        'contactable_id',
        'contactable_type',
    ];

    public static function is_retrievable(): bool
    {
        return true;
    }

    public function contactable()
    {
        return $this->morphTo();
    }

    public function replays()
    {
        return $this->morphMany(Replay::class, 'replayable');
    }
}
