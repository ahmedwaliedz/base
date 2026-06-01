<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardService
{
    private array $tableExists = [];

    private array $columnExists = [];

    public function homeViewData(): array
    {
        $now = now();
        $prev = $now->copy()->subMonth();

        $monthlyRaw = User::selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => "{$row->y}-{$row->m}");

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

        $adminMonthlyRaw = Admin::selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => "{$row->y}-{$row->m}");

        $adminMonthlyData = collect(range(5, 0))->map(function ($offset) use ($now, $adminMonthlyRaw) {
            $date = $now->copy()->subMonths($offset);

            return (int) ($adminMonthlyRaw->get("{$date->year}-{$date->month}")->cnt ?? 0);
        });

        $newThisMonth = (int) ($monthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $newLastMonth = (int) ($monthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);
        $newAdminsThisMonth = (int) ($adminMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $newAdminsLastMonth = (int) ($adminMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        $complaintMonthlyRaw = $this->safeMonthly('complaints', $now);
        $contactMonthlyRaw = $this->safeMonthly('contact_messages', $now);

        $activityData = collect(range(5, 0))->map(function ($offset) use ($now, $complaintMonthlyRaw, $contactMonthlyRaw) {
            $date = $now->copy()->subMonths($offset);
            $key = "{$date->year}-{$date->month}";

            return [
                'label' => $date->format('M'),
                'complaints' => (int) ($complaintMonthlyRaw->get($key)->cnt ?? 0),
                'contacts' => (int) ($contactMonthlyRaw->get($key)->cnt ?? 0),
            ];
        });

        $totalCategories = $this->safeCount('categories');
        $activeCategories = $this->safeCount('categories', ['is_active' => true]);
        $totalSliders = $this->safeCount('sliders');
        $activeSliders = $this->safeCount('sliders', ['is_active' => true]);
        $totalPosts = $this->safeCount('posts');
        $activePosts = $this->hasColumn('posts', 'is_active')
            ? $this->safeCount('posts', ['is_active' => true])
            : $totalPosts;
        $totalFaqs = $this->safeCount('faqs');
        $activeFaqs = $this->hasColumn('faqs', 'is_active')
            ? $this->safeCount('faqs', ['is_active' => true])
            : $totalFaqs;
        $totalComplaints = $this->safeCount('complaints');
        $pendingComplaints = $this->safeCount('complaints', ['status' => 'pending']);
        $resolvedComplaints = $totalComplaints - $pendingComplaints;
        $totalContacts = $this->safeCount('contact_messages');

        $newContactsMonth = (int) ($contactMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $prevContactsMonth = (int) ($contactMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);
        $complThisMonth = (int) ($complaintMonthlyRaw->get("{$now->year}-{$now->month}")->cnt ?? 0);
        $complLastMonth = (int) ($complaintMonthlyRaw->get("{$prev->year}-{$prev->month}")->cnt ?? 0);

        $hour = (int) $now->format('H');
        $greetingKey = $hour < 12 ? 'home_greeting_morning'
            : ($hour < 17 ? 'home_greeting_afternoon'
                : ($hour < 21 ? 'home_greeting_evening'
                    : 'home_greeting_night'));

        $ratioActiveUsers = $totalUsers > 0 ? (int) round($activeUsers / $totalUsers * 100) : 0;
        $ratioActiveCategories = $totalCategories > 0 ? (int) round($activeCategories / $totalCategories * 100) : 0;
        $ratioActiveSliders = $totalSliders > 0 ? (int) round($activeSliders / $totalSliders * 100) : 0;
        $ratioResolvedComplaints = $totalComplaints > 0 ? (int) round($resolvedComplaints / $totalComplaints * 100) : 0;

        $stats = [
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
            'greeting_key' => $greetingKey,
            'change_new_users' => $this->pctChange($newThisMonth, $newLastMonth),
            'change_blocked' => $this->pctChange(
                $blockedUsers,
                $totalUsers > 0 ? (int) round($totalUsers * 0.1) : 0
            ),
            'change_complaints' => $this->pctChange($complThisMonth, $complLastMonth),
            'change_contacts' => $this->pctChange($newContactsMonth, $prevContactsMonth),
            'change_admins' => $this->pctChange($newAdminsThisMonth, $newAdminsLastMonth),
            'activity_labels' => $activityData->pluck('label')->values(),
            'activity_complaints' => $activityData->pluck('complaints')->values(),
            'activity_contacts' => $activityData->pluck('contacts')->values(),
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
            'ratio_users' => $ratioActiveUsers,
            'ratio_categories' => $ratioActiveCategories,
            'ratio_sliders' => $ratioActiveSliders,
            'ratio_posts' => $totalPosts > 0 ? (int) round($activePosts / $totalPosts * 100) : 0,
            'ratio_faqs' => $totalFaqs > 0 ? (int) round($activeFaqs / $totalFaqs * 100) : 0,
            'ratio_complaints' => $ratioResolvedComplaints,
            'ratio_blocked' => $totalUsers > 0 ? (int) round($blockedUsers / $totalUsers * 100) : 0,
            'ratio_new_users' => $totalUsers > 0
                ? min((int) round($newThisMonth / max($totalUsers, 1) * 100 * 5), 100)
                : 0,
            'ratio_year_users' => $totalUsers > 0
                ? min((int) round((int) $monthlyData->sum('count') / $totalUsers * 100), 100)
                : 0,
            'latest_users' => User::latest()
                ->take(6)
                ->get(['id', 'name', 'image', 'phone', 'is_blocked', 'created_at']),
            'pending_complaints_list' => $this->hasTable('complaints')
                ? DB::table('complaints')
                    ->where('status', 'pending')
                    ->latest()
                    ->take(6)
                    ->get(['id', 'name', 'phone', 'email', 'subject', 'type', 'status', 'created_at'])
                    ->map(function ($row) {
                        $row->status_value = $row->status;
                        return $row;
                    })
                : collect(),
            'latest_contacts' => $this->hasTable('contact_messages')
                ? DB::table('contact_messages')
                    ->latest()
                    ->take(6)
                    ->get(['id', 'name', 'email', 'phone', 'subject', 'created_at'])
                : collect(),
            'dist_series' => collect([
                $totalUsers,
                $totalComplaints,
                $totalContacts,
                $totalCategories,
                $totalFaqs,
                $totalPosts,
                $totalSliders,
            ])->values(),
            'polar_series' => [
                $ratioActiveUsers,
                $ratioActiveCategories,
                $ratioActiveSliders,
                $ratioResolvedComplaints,
            ],
        ];

        $admin = auth('admin')->user();
        $isRtl = in_array(app()->getLocale(), ['ar', 'fa', 'he', 'ur'], true);
        $arrow = html_entity_decode($isRtl ? '&larr;' : '&rarr;', ENT_QUOTES, 'UTF-8');

        return compact('stats', 'admin', 'arrow');
    }

    private function safeCount(string $table, array $where = []): int
    {
        if (! $this->hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);
        foreach ($where as $column => $value) {
            $query->where($column, $value);
        }

        return $query->count();
    }

    private function safeMonthly(string $table, Carbon $now): Collection
    {
        if (! $this->hasTable($table)) {
            return collect();
        }

        return DB::table($table)
            ->selectRaw('YEAR(created_at) as y, MONTH(created_at) as m, COUNT(*) as cnt')
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('y', 'm')
            ->get()
            ->keyBy(fn ($row) => "{$row->y}-{$row->m}");
    }

    private function hasTable(string $table): bool
    {
        return $this->tableExists[$table] ??= Schema::hasTable($table);
    }

    private function hasColumn(string $table, string $column): bool
    {
        $key = "{$table}.{$column}";

        return $this->columnExists[$key] ??= $this->hasTable($table) && Schema::hasColumn($table, $column);
    }

    private function pctChange(int $current, int $previous): array
    {
        if ($previous === 0) {
            return ['value' => $current > 0 ? 100.0 : 0.0, 'up' => $current > 0];
        }

        $pct = round(($current - $previous) / $previous * 100, 1);

        return ['value' => abs($pct), 'up' => $pct >= 0];
    }
}
