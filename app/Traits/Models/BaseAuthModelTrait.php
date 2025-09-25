<?php
namespace App\Traits\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Notifications\Notifiable;

trait BaseAuthModelTrait {

    use Notifiable, BaseFilesTrait, GeneralTrait, FilterableTrait;

    // public function setPasswordAttribute($value) {
    //     if (! empty($value)) {
    //         $this->attributes['password'] = bcrypt($value);
    //     }
    // }

}
