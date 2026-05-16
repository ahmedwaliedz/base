<?php

namespace App\Services\Admin;

use App\Models\Social;
use App\Services\Admin\Base\CrudBaseService;

class SocialService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Social::class);
    }

    public function switchIsActive(int|string $id): bool
    {
        $social = Social::query()->findOrFail($id);
        $social->update(['is_active' => ! $social->is_active]);

        return (bool) $social->fresh()->is_active;
    }
}