<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    use HasFactory;

    public const RELATIONS = ['replayable', 'replaybyable'];

    protected $fillable = [
        'replay',
        'replayable_id',
        'replayable_type',
        'replaybyable_id',
        'replaybyable_type',
    ];

    public function replayable()
    {
        return $this->morphTo();
    }

    public function replaybyable()
    {
        return $this->morphTo();
    }
}
