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
        return parent::index($request, $where)->with('translations')->withCount(['children']);
    }

    public function createVars(): array
    {
        $parents = \App\Models\Category::with('translations')->whereNull('parent_id')->get();

        return [
            'parents' => $parents->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toArray(),
        ];
    }

    public function editVars($id = null): array
    {
        $parentsQuery = \App\Models\Category::with('translations')->whereNull('parent_id');

        if ($id) {
            $parentsQuery->where('id', '!=', $id);
        }

        $parents = $parentsQuery->get();

        return [
            'parents' => $parents->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toArray(),
        ];
    }

    public function switchIsActive(int|string $id): bool
    {
        $category = Category::query()->findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        return (bool) $category->fresh()->is_active;
    }
}
