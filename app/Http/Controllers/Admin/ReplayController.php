<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\ReplayService;

class ReplayController extends AdminBaseController
{
    public function __construct(ReplayService $replayService)
    {
        parent::__construct($replayService);
    }
}