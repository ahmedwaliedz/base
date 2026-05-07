<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function home(): \Illuminate\Contracts\View\View
    {
        $now = now();

        // Monthly registrations — single query for last 6 months
        $monthlyRaw = User::selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($r) => "{$r->y}-{$r->m}");

        $monthlyData = collect(range(5, 0))->map(function ($offset) use ($now, $monthlyRaw) {
            $date = $now->copy()->subMonths($offset);
            return [
                'label' => $date->format('M'),
                'count' => (int) ($monthlyRaw->get("{$date->year}-{$date->month}")->cnt ?? 0),
            ];
        });

        $totalUsers   = User::count();
        $activeUsers  = User::where('is_blocked', false)->count();
        $blockedUsers = User::where('is_blocked', true)->count();

        $stats = [
            'users_this_year'       => User::whereYear('created_at', $now->year)->count(),
            'top_package_purchases' => 0,
            'total_users'           => $totalUsers,
            'active_users'          => $activeUsers,
            'blocked_users'         => $blockedUsers,
            'new_this_month'        => (int) ($monthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0),
            'monthly_labels'        => $monthlyData->pluck('label')->values(),
            'monthly_counts'        => $monthlyData->pluck('count')->values(),
        ];

        return view('admin.home.index', compact('stats'));
    }
}
