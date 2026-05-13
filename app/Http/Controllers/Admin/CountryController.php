<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ServiceException;
use App\Services\Admin\CountryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        } catch (ServiceException $e) {
            return $this->respondWithFail($e->getMessage(), [], $e->getStatusCode());
        } catch (\Throwable $e) {
            Log::error('Unexpected error in switchActive', [
                'controller' => static::class,
                'model' => 'Country',
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->respondInternalError();
        }
    }

    /**
     * Aggregated statistics for the countries listing (same UX as users/admins statistics).
     */
    public function statistics(Request $request): Response
    {
        $now = Carbon::now();
        $base = $this->service->statisticsBaseQuery($request);

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

        return response()->view('admin.countries.parts.statistics', [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'today' => $today,
            'thisWeek' => $thisWeek,
            'thisMonth' => $thisMonth,
            'growth' => $growth,
        ]);
    }
}
