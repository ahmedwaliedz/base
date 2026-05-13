<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends AuthenticatableBaseController
{
    public function __construct(UserService $userService)
    {
        parent::__construct($userService);
    }

    public function show($id)
    {
        $vars = $this->service->show($id);
        /** @var User $user */
        $user = $vars['user'];

        $now = Carbon::now();

        $accountAgeDays = $user->created_at?->diffInDays($now) ?? 0;

        $sessionsCount = 0;
        $sessions = collect();
        if (Schema::hasTable('sessions')) {
            $sessionsCount = (int) DB::table('sessions')->where('user_id', $user->id)->count();
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderByDesc('last_activity')
                ->take(10)
                ->get();
        }

        $lastSession = $sessions->first();
        $lastLoginAt = $lastSession
            ? Carbon::createFromTimestamp((int) $lastSession->last_activity)
            : null;

        $verificationScore =
            ($user->email_verified_at ? 25 : 0)
            + ($user->phone_verified_at ? 25 : 0)
            + ($user->is_active ? 25 : 0)
            + ($user->is_complete_info ? 25 : 0);

        $recentOtps = method_exists($user, 'otps')
            ? $user->otps()->latest()->take(5)->get()
            : collect();

        $complaintsCount = $user->complaints()->count();
        $complaints = $user->complaints()->latest()->take(20)->get();
        $contactsCount = $user->contactMessages()->count();
        $contacts = $user->contactMessages()->latest()->take(20)->get();

        $stats = [
            'account_age_days' => $accountAgeDays,
            'last_login_at' => $lastLoginAt,
            'sessions_count' => $sessionsCount,
            'verification_score' => $verificationScore,
            'complaints_count' => $complaintsCount,
            'contacts_count' => $contactsCount,
        ];

        return view('admin.users.show', array_merge($vars, [
            'stats' => $stats,
            'sessions' => $sessions,
            'recentOtps' => $recentOtps,
            'complaints' => $complaints,
            'complaintsCount' => $complaintsCount,
            'contacts' => $contacts,
            'contactsCount' => $contactsCount,
        ]));
    }

    public function statistics(Request $request)
    {
        $now = Carbon::now();
        $base = $this->service->index($request);

        $total = (clone $base)->count();
        $active = (clone $base)->where('is_blocked', false)->count();
        $blocked = (clone $base)->where('is_blocked', true)->count();
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

        return response()->view(
            'admin.users.parts.statistics',
            [
                'total' => $total,
                'active' => $active,
                'blocked' => $blocked,
                'today' => $today,
                'thisWeek' => $thisWeek,
                'thisMonth' => $thisMonth,
                'growth' => $growth,
            ]
        );
    }
}
