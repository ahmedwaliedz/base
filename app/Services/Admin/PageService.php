<?php

namespace App\Services\Admin;

use App\Models\Page;
use App\Services\Admin\Base\CrudBaseService;

class PageService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Page::class);
    }

    public function switchType(int|string $id): bool
    {
        $page = Page::query()->findOrFail($id);
        $page->update(['type' => ! $page->type]);

        return (bool) $page->fresh()->type;
    }
}