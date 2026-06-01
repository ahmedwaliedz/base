<?php

namespace App\Services\Admin;

use App\Models\City;
use App\Services\Admin\Base\CrudBaseService;

class CityService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(City::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)
            ->with(['translations', 'region' => fn ($q) => $q->with('translations')])
            ->withCount('districts');
    }

    public function createVars(): array
    {
        return [
            'regions' => \App\Models\Region::with('translations')
                ->get()
                ->map(fn($r) => ['id' => $r->id, 'name' => $r->name])
                ->toArray(),
        ];
    }

    public function editVars($id = null): array
    {
        return $this->createVars();
    }

    public function switchIsActive(int|string $id): bool
    {
        $city = City::query()->findOrFail($id);
        $city->update(['is_active' => ! $city->is_active]);

        return (bool) $city->fresh()->is_active;
    }
}
