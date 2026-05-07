<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LandingController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        $stats = [
            'active_users'     => User::where('is_blocked', false)->count(),
            'total_branches'   => $this->safeCount(['barns', 'farms', 'hutches']),
            'total_points'     => $this->safeSum(['points', 'user_points', 'reward_points'], 'amount'),
            'total_categories' => Schema::hasTable('categories') ? DB::table('categories')->count() : 0,
        ];

        return view('landing.index', [
            'stats'    => $stats,
            'settings' => cache()->get('settings', []),
        ]);
    }

    /** Returns count from the first table in $tables that exists, else 0. */
    private function safeCount(array $tables): int
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                return DB::table($table)->count();
            }
        }
        return 0;
    }

    /** Returns SUM($column) from the first matching table that exists, else 0. */
    private function safeSum(array $tables, string $column): int
    {
        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                return (int) DB::table($table)->sum($column);
            }
        }
        return 0;
    }
}
