<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Complaint extends Model
{
    use GeneralTrait, HasFactory, FilterableTrait, SoftDeletes;

    public const RELATIONS = ['complainantable', 'images', 'replays'];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'complaint',
        'type',
        'status',
        'is_read',
        'complainantable_id',
        'complainantable_type',
    ];

    public static function is_retrievable(): bool
    {
        return true;
    }

    public function complainantable()
    {
        return $this->morphTo();
    }

    public function getStatusAttribute($value): ?ComplaintStatus
    {
        if ($value === null) {
            return null;
        }

        $status = ComplaintStatus::tryFrom((string) $value);

        if ($status === null) {
            Log::warning('Invalid complaint status enum value encountered', [
                'complaint_id' => $this->getKey(),
                'attribute' => 'status',
                'value' => $value,
            ]);
            return null;
        }

        return $status;
    }

    public function getTypeAttribute($value): ?ComplaintType
    {
        if ($value === null) {
            return null;
        }

        $type = ComplaintType::tryFrom((string) $value);

        if ($type === null) {
            Log::warning('Invalid complaint type enum value encountered', [
                'complaint_id' => $this->getKey(),
                'attribute' => 'type',
                'value' => $value,
            ]);
            return null;
        }

        return $type;
    }

    public function images()
    {
        return $this->hasMany(ComplaintImage::class);
    }

    public function replays()
    {
        return $this->morphMany(Replay::class, 'replayable');
    }
}
