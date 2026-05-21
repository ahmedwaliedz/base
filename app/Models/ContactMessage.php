<?php

namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use GeneralTrait, HasFactory, FilterableTrait;

    public const RELATIONS = ['contactable', 'replays'];

    public const PATH_NAME = 'contact-messages';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'message',
        'contactable_id',
        'contactable_type',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }

    public function replays()
    {
        return $this->morphMany(Replay::class, 'replayable');
    }
}
