<?php

namespace App\Services\Admin;

use App\Models\Replay;
use App\Services\Admin\Base\CrudBaseService;

class ReplayService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Replay::class);
    }
}