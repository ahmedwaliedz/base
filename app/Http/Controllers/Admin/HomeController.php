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
        $now = now();
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

        $totalUsers = User::count();
        $activeUsers = User::where('is_blocked', false)->count();
        $blockedUsers = User::where('is_blocked', true)->count();
        $totalAdmins = Admin::count();
        $newUsersToday = User::whereDate('created_at', $now->toDateString())->count();

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
        $newAdminsThisMonth = (int) ($adminMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $newAdminsLastMonth = (int) ($adminMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        /* ── Safe helpers ───────────────────────────────────── */
        $safeCount = function (string $table, array $where = []): int {
            if (! Schema::hasTable($table)) {
                return 0;
            }
            $q = \DB::table($table);
            foreach ($where as $col => $val) {
                $q->where($col, $val);
            }

            return $q->count();
        };

        $safeMonthly = function (string $table) use ($now): \Illuminate\Support\Collection {
            if (! Schema::hasTable($table)) {
                return collect();
            }

            return \DB::table($table)
                ->selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
                ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
                ->groupBy('y', 'm')
                ->get()
                ->keyBy(fn ($r) => "{$r->y}-{$r->m}");
        };

        /* ── Optional tables ────────────────────────────────── */
        $complaintMonthlyRaw = $safeMonthly('complaints');
        $contactMonthlyRaw = $safeMonthly('contact_messages');

        $activityData = collect(range(5, 0))->map(function ($offset) use ($now, $complaintMonthlyRaw, $contactMonthlyRaw) {
            $date = $now->copy()->subMonths($offset);
            $key = "{$date->year}-{$date->month}";

            return [
                'label' => $date->format('M'),
                'complaints' => (int) ($complaintMonthlyRaw->get($key)->cnt ?? 0),
                'contacts' => (int) ($contactMonthlyRaw->get($key)->cnt ?? 0),
            ];
        });

        $totalCategories = $safeCount('categories');
        $activeCategories = $safeCount('categories', ['is_active' => true]);
        $totalSliders = $safeCount('sliders');
        $activeSliders = $safeCount('sliders', ['is_active' => true]);
        $totalPosts = $safeCount('posts');
        $activePosts = Schema::hasTable('posts') && Schema::hasColumn('posts', 'is_active')
                                ? $safeCount('posts', ['is_active' => true])
                                : $totalPosts;
        $totalFaqs = $safeCount('faqs');
        $activeFaqs = Schema::hasTable('faqs') && Schema::hasColumn('faqs', 'is_active')
                                ? $safeCount('faqs', ['is_active' => true])
                                : $totalFaqs;
        $totalComplaints = $safeCount('complaints');
        $pendingComplaints = $safeCount('complaints', ['status' => 'pending']);
        $resolvedComplaints = $totalComplaints - $pendingComplaints;
        $totalContacts = $safeCount('contact_messages');

        $newContactsMonth = (int) ($contactMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $prevContactsMonth = (int) ($contactMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);
        $complThisMonth = (int) ($complaintMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $complLastMonth = (int) ($complaintMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        /* ── Time-aware greeting ──────────────────────────── */
        $hour = (int) $now->format('H');
        $greetingKey = $hour < 12 ? 'home_greeting_morning'
                    : ($hour < 17 ? 'home_greeting_afternoon'
                    : ($hour < 21 ? 'home_greeting_evening'
                    : 'home_greeting_night'));

        /* ── Ratios for polar (all in %, consistent unit) ──── */
        $ratioActiveUsers = $totalUsers > 0 ? (int) round($activeUsers / $totalUsers * 100) : 0;
        $ratioActiveCategories = $totalCategories > 0 ? (int) round($activeCategories / $totalCategories * 100) : 0;
        $ratioActiveSliders = $totalSliders > 0 ? (int) round($activeSliders / $totalSliders * 100) : 0;
        $ratioResolvedCompl = $totalComplaints > 0 ? (int) round($resolvedComplaints / $totalComplaints * 100) : 0;

        $stats = [
            /* Users */
            'users_this_year' => User::whereYear('created_at', $now->year)->count(),
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'blocked_users' => $blockedUsers,
            'new_this_month' => $newThisMonth,
            'new_today' => $newUsersToday,
            'total_admins' => $totalAdmins,
            'monthly_labels' => $monthlyData->pluck('label')->values(),
            'monthly_counts' => $monthlyData->pluck('count')->values(),
            'admin_monthly_counts' => $adminMonthlyData->values(),

            /* Greeting */
            'greeting_key' => $greetingKey,

            /* Month-over-month deltas (signed, so blocked direction is correct) */
            'change_new_users' => self::pctChange($newThisMonth, $newLastMonth),
            'change_blocked' => self::pctChange($blockedUsers,
                $totalUsers > 0 ? (int) round($totalUsers * 0.1) : 0),
            'change_complaints' => self::pctChange($complThisMonth, $complLastMonth),
            'change_contacts' => self::pctChange($newContactsMonth, $prevContactsMonth),
            'change_admins' => self::pctChange($newAdminsThisMonth, $newAdminsLastMonth),

            /* Activity charts */
            'activity_labels' => $activityData->pluck('label')->values(),
            'activity_complaints' => $activityData->pluck('complaints')->values(),
            'activity_contacts' => $activityData->pluck('contacts')->values(),

            /* Content */
            'total_posts' => $totalPosts,
            'active_posts' => $activePosts,
            'total_categories' => $totalCategories,
            'active_categories' => $activeCategories,
            'total_sliders' => $totalSliders,
            'active_sliders' => $activeSliders,
            'total_faqs' => $totalFaqs,
            'active_faqs' => $activeFaqs,
            'total_complaints' => $totalComplaints,
            'pending_complaints' => $pendingComplaints,
            'resolved_complaints' => $resolvedComplaints,
            'total_contacts' => $totalContacts,
            'new_contacts_month' => $newContactsMonth,

            /* Ratios 0-100 (real %) */
            'ratio_users' => $ratioActiveUsers,
            'ratio_categories' => $ratioActiveCategories,
            'ratio_sliders' => $ratioActiveSliders,
            'ratio_posts' => $totalPosts > 0 ? (int) round($activePosts / $totalPosts * 100) : 0,
            'ratio_faqs' => $totalFaqs > 0 ? (int) round($activeFaqs / $totalFaqs * 100) : 0,
            'ratio_complaints' => $ratioResolvedCompl,
            'ratio_blocked' => $totalUsers > 0 ? (int) round($blockedUsers / $totalUsers * 100) : 0,
            'ratio_new_users' => $totalUsers > 0
                                        ? min((int) round($newThisMonth / max($totalUsers, 1) * 100 * 5), 100)
                                        : 0,
            'ratio_year_users' => $totalUsers > 0
                                        ? min((int) round((int) $monthlyData->sum('count') / $totalUsers * 100), 100)
                                        : 0,

            /* Quick-action tables */
            'latest_users' => User::latest()
                ->take(6)
                ->get(['id', 'name', 'image', 'phone', 'is_blocked', 'created_at']),

            'pending_complaints_list' => Schema::hasTable('complaints')
                ? \DB::table('complaints')
                    ->where('status', 'pending')
                    ->latest()->take(6)
                    ->get(['id', 'name', 'phone', 'email', 'subject', 'type', 'status', 'created_at'])
                : collect(),

            'latest_contacts' => Schema::hasTable('contact_messages')
                ? \DB::table('contact_messages')
                    ->latest()->take(6)
                    ->get(['id', 'name', 'email', 'phone', 'subject', 'created_at'])
                : collect(),

            /* Donut: Platform distribution */
            'dist_series' => collect([
                $totalUsers, $totalComplaints, $totalContacts,
                $totalCategories, $totalFaqs, $totalPosts, $totalSliders,
            ])->values(),

            /* Polar: only ratios — same unit (%) */
            'polar_series' => [
                $ratioActiveUsers,
                $ratioActiveCategories,
                $ratioActiveSliders,
                $ratioResolvedCompl,
            ],
        ];

        /* Direction-aware arrow glyph (← in RTL = "go forward to that page") */
        $admin = auth('admin')->user();
        $isRtl = in_array(app()->getLocale(), ['ar', 'fa', 'he', 'ur'], true);
        $arrow = $isRtl ? '←' : '→';

        return view('admin.home.index', compact('stats', 'admin', 'arrow'));
    }

    private static function pctChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return ['value' => $current > 0 ? 100.0 : 0.0, 'up' => $current > 0];
        }
        $pct = round(($current - $previous) / $previous * 100, 1);

        return ['value' => abs($pct), 'up' => $pct >= 0];
    }
}
