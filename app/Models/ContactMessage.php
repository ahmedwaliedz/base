<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

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
