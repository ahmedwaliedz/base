<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends AuthenticatableBaseController
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

    public function statistics(Request $request)
    {
        $base = $this->service->index($request);

        $now = Carbon::now();

        $total      = (clone $base)->count();
        $active     = (clone $base)->where('is_blocked', false)->count();
        $blocked    = (clone $base)->where('is_blocked', true)->count();
        $today      = (clone $base)->whereDate('created_at', $now->toDateString())->count();
        $thisWeek   = (clone $base)->where('created_at', '>=', $now->copy()->startOfWeek())->count();
        $thisMonth  = (clone $base)->where('created_at', '>=', $now->copy()->startOfMonth())->count();
        $lastMonth  = (clone $base)
            ->whereBetween('created_at', [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ])
            ->count();

        $growth = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
            : ($thisMonth > 0 ? 100.0 : 0.0);

        return response()->view(
            'admin.users.parts.statistics',
            compact('total', 'active', 'blocked', 'today', 'thisWeek', 'thisMonth', 'growth')
        );
    }
}
