<?php
namespace App\Traits\Models;

trait BaseAuthModelTrait {

    public function scopeStatus($query, $status) {
        if ($status === 'active') {
            return $query->where('is_blocked', false);
        } elseif ($status === 'blocked') {
            return $query->where('is_blocked', true);
        }
        return $query;
    }

}
