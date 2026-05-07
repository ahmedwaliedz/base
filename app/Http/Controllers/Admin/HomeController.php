<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function home(): \Illuminate\Contracts\View\View
    {
        $now = now();

        /* ── Users (always present) ─────────────────────── */
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

        /* ── Safe table query helper ────────────────────── */
        $safeCount = function (string $table, array $where = []): int {
            if (! Schema::hasTable($table)) return 0;
            $q = \DB::table($table);
            foreach ($where as $col => $val) { $q->where($col, $val); }
            return $q->count();
        };

        $safeMonthly = function (string $table) use ($now): \Illuminate\Support\Collection {
            if (! Schema::hasTable($table)) return collect();
            return \DB::table($table)
                ->selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
                ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
                ->groupBy('y', 'm')
                ->get()
                ->keyBy(fn ($r) => "{$r->y}-{$r->m}");
        };

        /* ── Optional tables ────────────────────────────── */
        $complaintMonthlyRaw = $safeMonthly('complaints');
        $contactMonthlyRaw   = $safeMonthly('contact_messages');

        $activityData = collect(range(5, 0))->map(function ($offset) use ($now, $complaintMonthlyRaw, $contactMonthlyRaw) {
            $date = $now->copy()->subMonths($offset);
            $key  = "{$date->year}-{$date->month}";
            return [
                'label'      => $date->format('M'),
                'complaints' => (int) ($complaintMonthlyRaw->get($key)->cnt ?? 0),
                'contacts'   => (int) ($contactMonthlyRaw->get($key)->cnt ?? 0),
            ];
        });

        $totalCategories  = $safeCount('categories');
        $activeCategories = $safeCount('categories', ['is_active' => true]);
        $totalSliders     = $safeCount('sliders');
        $activeSliders    = $safeCount('sliders', ['is_active' => true]);
        $totalPosts       = $safeCount('posts');
        $totalFaqs        = $safeCount('faqs');
        $totalComplaints  = $safeCount('complaints');
        $pendingComplaints = $safeCount('complaints', ['status' => 'pending']);
        $totalContacts    = $safeCount('contact_messages');
        $newContactsMonth = (int) ($contactMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);

        $stats = [
            /* Users */
            'users_this_year'       => User::whereYear('created_at', $now->year)->count(),
            'top_package_purchases' => 0,
            'total_users'           => $totalUsers,
            'active_users'          => $activeUsers,
            'blocked_users'         => $blockedUsers,
            'new_this_month'        => (int) ($monthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0),
            'monthly_labels'        => $monthlyData->pluck('label')->values(),
            'monthly_counts'        => $monthlyData->pluck('count')->values(),

            /* Activity charts */
            'activity_labels'     => $activityData->pluck('label')->values(),
            'activity_complaints' => $activityData->pluck('complaints')->values(),
            'activity_contacts'   => $activityData->pluck('contacts')->values(),

            /* Content */
            'total_posts'        => $totalPosts,
            'total_categories'   => $totalCategories,
            'active_categories'  => $activeCategories,
            'total_sliders'      => $totalSliders,
            'active_sliders'     => $activeSliders,
            'total_faqs'         => $totalFaqs,
            'total_complaints'   => $totalComplaints,
            'pending_complaints' => $pendingComplaints,
            'total_contacts'     => $totalContacts,
            'new_contacts_month' => $newContactsMonth,

            /* Radial active ratios (0-100) */
            'ratio_users'      => $totalUsers      > 0 ? round($activeUsers      / $totalUsers      * 100) : 0,
            'ratio_categories' => $totalCategories > 0 ? round($activeCategories / $totalCategories * 100) : 0,
            'ratio_sliders'    => $totalSliders    > 0 ? round($activeSliders    / $totalSliders    * 100) : 0,
        ];

        return view('admin.home.index', compact('stats'));
    }
}
