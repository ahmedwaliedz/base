<?php

namespace App\Services\Admin;

use App\Models\District;
use App\Services\Admin\Base\CrudBaseService;

class DistrictService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(District::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)
            ->with(['city' => fn ($q) => $q->with('translations')]);
    }

    public function switchIsActive(int|string $id): bool
    {
        $district = District::query()->findOrFail($id);
        $district->update(['is_active' => ! $district->is_active]);

        return (bool) $district->fresh()->is_active;
    }
}