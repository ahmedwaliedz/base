<?php

namespace App\Services\Admin;

use App\Enums\PageType;
use App\Models\Page;
use App\Services\Admin\Base\CrudBaseService;

class PageService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Page::class);
    }

    public function switchType(int|string $id): PageType
    {
        $page = Page::query()->findOrFail($id);
        $cases = PageType::cases();
        $currentIndex = array_search($page->type, $cases, true);
        $nextIndex = ($currentIndex + 1) % count($cases);
        $nextType = $cases[$nextIndex];
        $page->update(['type' => $nextType->value]);

        return $nextType;
    }
}