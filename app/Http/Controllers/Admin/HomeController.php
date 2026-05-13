<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function home(): View
    {
        return view('admin.home.index', $this->dashboardService->homeViewData());
    }
}
