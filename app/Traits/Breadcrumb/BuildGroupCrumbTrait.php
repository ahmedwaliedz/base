<?php

namespace App\Traits\Breadcrumb;


trait BuildGroupCrumbTrait
{
    protected static function buildGroupCrumb(string $groupKey): array
    {
        $group = config('admin_groups')[$groupKey] ?? [];
        return [
            'title'  => $groupKey .'.index',
            'icon'   => $group['icon'] ?? '',
            'url'    => '#',
            'active' => false,
        ];
    }
}
