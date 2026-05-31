<?php

namespace App\Services\Admin;

use App\Models\Country;
use App\Services\Admin\Base\CrudBaseService;

class CountryService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Country::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->with('translations')->withCount(['regions', 'cities']);
    }

    /**
     * Same filters as {@see index()} but without withCount (for statistics cards SQL).
     */
    public function statisticsBaseQuery($request, $where = [])
    {
        return parent::index($request, $where);
    }

    public function switchIsActive(int|string $id): bool
    {
        $country = Country::query()->findOrFail($id);
        $country->update(['is_active' => ! $country->is_active]);

        return (bool) $country->fresh()->is_active;
    }

    /**
     * @return array<string, mixed>
     */
    public function show($id): array
    {
        $data = parent::show($id);
        /** @var Country $country */
        $country = $data[$this->lowerClassName];
        $country->load('translations');

        $data['regions_count'] = $country->regions()->count();
        $data['cities_count'] = $country->cities()->count();
        $data['country_regions'] = $country->regions()
            ->with('translations')
            ->withCount('cities')
            ->orderBy('id')
            ->get();
        $data['country_cities'] = $country->cities()
            ->with('translations')
            ->with(['region' => fn ($q) => $q->with('translations')])
            ->orderBy('id')
            ->get();

        return $data;
    }
}
