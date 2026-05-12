<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\CountryService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class CountryController extends AdminBaseController
{
    public function __construct(CountryService $countryService)
    {
        parent::__construct($countryService);
    }

    /**
     * Toggle country active flag (table row switch).
     */
    public function switchActive($id): JsonResponse
    {
        try {
            $isActive = $this->service->switchIsActive($id);

            return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
                'is_active' => $isActive,
            ]);
        } catch (Exception $e) {
            return $this->respondWithFail($e->getMessage());
        }
    }

    /**
     * Aggregated statistics for the countries listing (same UX as users/admins statistics).
     */
    public function statistics(Request $request): Response
    {
        $base = $this->service->statisticsBaseQuery($request);

        $now = Carbon::now();

        $total = (clone $base)->count();
        $active = (clone $base)->where('is_active', true)->count();
        $inactive = (clone $base)->where('is_active', false)->count();
        $today = (clone $base)->whereDate('created_at', $now->toDateString())->count();
        $thisWeek = (clone $base)->where('created_at', '>=', $now->copy()->startOfWeek())->count();
        $thisMonth = (clone $base)->where('created_at', '>=', $now->copy()->startOfMonth())->count();
        $lastMonth = (clone $base)
            ->whereBetween('created_at', [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ])
            ->count();

        $growth = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
            : ($thisMonth > 0 ? 100.0 : 0.0);

        return response()->view('admin.countries.parts.statistics', compact(
            'total',
            'active',
            'inactive',
            'today',
            'thisWeek',
            'thisMonth',
            'growth'
        ));
    }
}
