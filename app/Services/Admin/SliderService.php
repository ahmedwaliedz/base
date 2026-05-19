<?php

namespace App\Services\Admin;

use App\Models\Slider;
use App\Services\Admin\Base\CrudBaseService;

class SliderService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Slider::class);
    }
}
