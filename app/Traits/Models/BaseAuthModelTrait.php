<?php
namespace App\Traits\Models;

use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Notifications\Notifiable;

trait BaseAuthModelTrait {

    use Notifiable ,BaseFilesTrait ,GeneralTrait;

    public function scopeStatus($query, $status) {
        if ($status === 'active') {
            return $query->where('is_blocked', false);
        } elseif ($status === 'blocked') {
            return $query->where('is_blocked', true);
        }
        return $query;
    }

}
