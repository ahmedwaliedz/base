<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function home(): \Illuminate\Contracts\View\View
    {
        $stats = [
            'users_this_year' => User::query()->whereYear('created_at', now()->year)->count(),
            // Replace with a real query when package or subscription purchases exist in the database.
            'top_package_purchases' => 0,
        ];

        return view('admin.home.index', compact('stats'));
    }
}
