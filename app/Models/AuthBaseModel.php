<?php
namespace App\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\HandleNumbersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class AuthBaseModel extends BaseModel {

    use HasFactory, Notifiable, SoftDeletes, GeneralTrait, HandleNumbersTrait, FilterableTrait;

    public function setFullPhoneAttribute(string $value): void {
        $this->attributes['full_phone'] = $this->attributes['country_code'] . $this->attributes['phone'];
    }
}