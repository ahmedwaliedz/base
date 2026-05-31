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

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->with('translations');
    }

    public function switchActive(int|string $id): bool
    {
        $slider = Slider::query()->findOrFail($id);
        $slider->update(['is_active' => ! $slider->is_active]);

        return (bool) $slider->fresh()->is_active;
    }
}
