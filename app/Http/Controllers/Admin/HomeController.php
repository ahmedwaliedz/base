<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function home(): \Illuminate\Contracts\View\View
    {
        $now  = now();
        $prev = $now->copy()->subMonth();

        /* ── Users ──────────────────────────────────────────── */
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
        $totalAdmins  = Admin::count();

        /* ── Admins monthly ─────────────────────────────────── */
        $adminMonthlyRaw = Admin::selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($r) => "{$r->y}-{$r->m}");

        $adminMonthlyData = collect(range(5, 0))->map(function ($offset) use ($now, $adminMonthlyRaw) {
            $date = $now->copy()->subMonths($offset);
            return (int) ($adminMonthlyRaw->get("{$date->year}-{$date->month}")->cnt ?? 0);
        });

        $newThisMonth = (int) ($monthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $newLastMonth = (int) ($monthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        /* ── Safe helpers ───────────────────────────────────── */
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

        /* ── Optional tables ────────────────────────────────── */
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

        $totalCategories   = $safeCount('categories');
        $activeCategories  = $safeCount('categories', ['is_active' => true]);
        $totalSliders      = $safeCount('sliders');
        $activeSliders     = $safeCount('sliders', ['is_active' => true]);
        $totalPosts        = $safeCount('posts');
        $totalFaqs         = $safeCount('faqs');
        $totalComplaints   = $safeCount('complaints');
        $pendingComplaints = $safeCount('complaints', ['status' => 'pending']);
        $totalContacts     = $safeCount('contact_messages');
        $newContactsMonth  = (int) ($contactMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $prevContactsMonth = (int) ($contactMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        $complThisMonth = (int) ($complaintMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $complLastMonth = (int) ($complaintMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        $stats = [
            /* Users */
            'users_this_year'       => User::whereYear('created_at', $now->year)->count(),
            'top_package_purchases' => 0,
            'total_users'           => $totalUsers,
            'active_users'          => $activeUsers,
            'blocked_users'         => $blockedUsers,
            'new_this_month'        => $newThisMonth,
            'total_admins'          => $totalAdmins,
            'monthly_labels'        => $monthlyData->pluck('label')->values(),
            'monthly_counts'        => $monthlyData->pluck('count')->values(),
            'admin_monthly_counts'  => $adminMonthlyData->values(),

            /* Month-over-month changes */
            'change_new_users'   => self::pctChange($newThisMonth,    $newLastMonth),
            'change_blocked'     => self::pctChange($blockedUsers,     $totalUsers > 0 ? (int)round($totalUsers * 0.1) : 0),
            'change_complaints'  => self::pctChange($complThisMonth,  $complLastMonth),
            'change_contacts'    => self::pctChange($newContactsMonth,$prevContactsMonth),

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

            /* Ratios 0-100 (for progress bars & polar chart) */
            'ratio_users'      => $totalUsers      > 0 ? round($activeUsers      / $totalUsers      * 100) : 0,
            'ratio_categories' => $totalCategories > 0 ? round($activeCategories / $totalCategories * 100) : 0,
            'ratio_sliders'    => $totalSliders    > 0 ? round($activeSliders    / $totalSliders    * 100) : 0,
            'ratio_complaints' => $totalComplaints > 0 ? round(($totalComplaints - $pendingComplaints) / $totalComplaints * 100) : 0,
            'ratio_contacts'   => $totalContacts   > 0 ? min(round($newContactsMonth / max($totalContacts, 1) * 100 * 10), 100) : 0,

            /* Quick-action tables */
            'latest_users'      => User::latest()
                ->take(6)
                ->get(['id','name','image','phone','is_blocked','created_at']),

            'pending_complaints_list' => Schema::hasTable('complaints')
                ? \DB::table('complaints')
                    ->where('status', 'pending')
                    ->latest()->take(6)
                    ->get(['id','name','phone','subject','type','status','created_at'])
                : collect(),

            'latest_contacts' => Schema::hasTable('contact_messages')
                ? \DB::table('contact_messages')
                    ->latest()->take(6)
                    ->get(['id','name','email','phone','subject','created_at'])
                : collect(),

            /* Platform distribution (for donut) */
            'dist_series' => collect([
                'users'       => $totalUsers,
                'complaints'  => $totalComplaints,
                'contacts'    => $totalContacts,
                'categories'  => $totalCategories,
                'faqs'        => $totalFaqs,
                'posts'       => $totalPosts,
                'sliders'     => $totalSliders,
            ])->values(),
        ];

        return view('admin.home.index', compact('stats'));
    }

    private static function pctChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return ['value' => $current > 0 ? 100.0 : 0.0, 'up' => true];
        }
        $pct = round(($current - $previous) / $previous * 100, 1);
        return ['value' => abs($pct), 'up' => $pct >= 0];
    }
}
