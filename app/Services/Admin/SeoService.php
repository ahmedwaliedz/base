<?php

namespace App\Services\Admin;

use App\Models\Seo;
use App\Services\Admin\Base\CrudBaseService;

class SeoService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Seo::class);
    }
}