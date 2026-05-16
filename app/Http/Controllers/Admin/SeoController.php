<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\SeoService;

class SeoController extends AdminBaseController
{
    public function __construct(SeoService $seoService)
    {
        parent::__construct($seoService);
    }
}