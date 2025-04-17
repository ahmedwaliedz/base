<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    use HasFactory;

    protected $fillable = [
        'replay',
    ];

    public function replayable()
    {
        return $this->morphTo();
    }
}
