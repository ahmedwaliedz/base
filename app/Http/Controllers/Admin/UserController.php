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

        $total = (clone $base)->count();
        $active = (clone $base)->where('is_blocked', false)->count();
        $inactive = (clone $base)->where('is_blocked', true)->count();
        $today = (clone $base)->whereDate('created_at', Carbon::today())->count();

        return response()->view('admin.users.parts.statistics', compact('total', 'active', 'inactive', 'today'));
    }
}
