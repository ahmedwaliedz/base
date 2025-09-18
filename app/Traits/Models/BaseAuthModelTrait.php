<?php
namespace App\Traits\Models;

use Illuminate\Notifications\Notifiable;

trait BaseAuthModelTrait {

    use Notifiable;

    public function scopeStatus($query, $status) {
        if ($status === 'active') {
            return $query->where('is_blocked', false);
        } elseif ($status === 'blocked') {
            return $query->where('is_blocked', true);
        }
        return $query;
    }

}
