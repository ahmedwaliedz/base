<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'complaint_id',
        'file_type',
        'file_name',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
