<?php

namespace App\Traits\Breadcrumb;

trait BuildChildCrumbTrait
{
    protected static function buildChildCrumb(string $parentKey, string $childKey, array $parentData): array
    {
        $childData = $parentData['childs'][$childKey] ?? [];
        return [
            'title'  => "{$parentKey}.{$childKey}",
            'icon'   => $childData['icon'] ?? '<i class="ti ti-unlink"></i>',
            'url'    => route("admin.{$parentKey}.{$childKey}"),
            'active' => true,
        ];
    }
}
