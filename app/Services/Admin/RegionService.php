<?php

namespace App\Services\Admin;

use App\Models\Region;
use App\Services\Admin\Base\CrudBaseService;

class RegionService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Region::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->with(['country' => fn ($q) => $q->with('translations')])->withCount('cities');
    }

    public function show($id)
    {
        $data = parent::show($id);
        $data['model']->load(['cities' => fn($q) => $q->with('translations')->withCount('districts')]);
        return $data;
    }

    public function editVars($id = null): array
    {
        return [
            'countries' => \App\Models\Country::get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toArray(),
        ];
    }

    public function switchIsActive(int|string $id): bool
    {
        $region = Region::query()->findOrFail($id);
        $region->update(['is_active' => ! $region->is_active]);

        return (bool) $region->fresh()->is_active;
    }
}