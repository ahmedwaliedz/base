<?php

namespace App\Models;

use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'subject',
        'complaint',
        'type',
        'status',
        'complainantable_id',
        'complainantable_type',
    ];

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
            $this->logInvalidEnumValue('status', $value);
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
            $this->logInvalidEnumValue('type', $value);
            return null;
        }

        return $type;
    }

    private function logInvalidEnumValue(string $attribute, mixed $value): void
    {
        Log::warning('Invalid complaint enum value encountered', [
            'complaint_id' => $this->getKey(),
            'attribute' => $attribute,
            'value' => $value,
        ]);
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
