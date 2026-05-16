<?php

namespace App\Services\Admin;

use App\Models\IntroPage;
use App\Services\Admin\Base\CrudBaseService;

class IntroPageService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(IntroPage::class);
    }

    public function switchIsActive(int|string $id): bool
    {
        $introPage = IntroPage::query()->findOrFail($id);
        $introPage->update(['is_active' => ! $introPage->is_active]);

        return (bool) $introPage->fresh()->is_active;
    }
}