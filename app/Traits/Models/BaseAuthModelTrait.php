<?php
namespace App\Traits\Models;

use App\Traits\Filters\FilterableTrait;
use App\Traits\GeneralTrait;
use App\Traits\Upload\BaseFilesTrait;
use Illuminate\Notifications\Notifiable;

trait BaseAuthModelTrait {

    use Notifiable, BaseFilesTrait, GeneralTrait, FilterableTrait;

    public function setPasswordAttribute($value) {
        if (! empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function statusData() {
        return [
            'label' => $this->is_blocked ? __('admin/main.blocked') : __('admin/main.active'),
            'class' => $this->is_blocked ? 'bg-label-warning' : 'bg-label-success',
        ];
    }

    // Accessor used by export to get a plain status label
    public function getStatusLabelAttribute() {
        return $this->statusData()['label'] ?? null;
    }
}
