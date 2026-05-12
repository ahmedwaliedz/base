<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminType;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class AdminController extends AuthenticatableBaseController
{
    public function __construct(AdminService $adminService)
    {
        parent::__construct($adminService);
    }

    /**
     * Aggregated statistics for the admins listing (same UX pattern as users.statistics).
     */
    public function statistics(Request $request): Response
    {
        $base = $this->service->index($request);

        $now = Carbon::now();

        $total = (clone $base)->count();
        $active = (clone $base)->where('is_blocked', false)->count();
        $blocked = (clone $base)->where('is_blocked', true)->count();
        $superAdmins = (clone $base)->where('type', AdminType::SUPER_ADMIN->value)->count();
        $rolesInUse = (clone $base)->whereNotNull('role_id')->distinct()->count('role_id');
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

        return response()->view(
            'admin.admins.parts.statistics',
            compact('total', 'active', 'blocked', 'superAdmins', 'rolesInUse', 'thisMonth', 'growth')
        );
    }
}
