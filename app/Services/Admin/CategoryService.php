<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Services\Admin\Base\CrudBaseService;

class CategoryService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Category::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->withCount(['children']);
    }

    public function switchIsActive(int|string $id): bool
    {
        $category = Category::query()->findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        return (bool) $category->fresh()->is_active;
    }
}
