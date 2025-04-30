<?php

namespace App\Traits\Breadcrumb;

use App\Traits\RouteTrait;

trait BuildChildCrumbTrait
{
    use RouteTrait;
    protected static function buildChildCrumb(string $parentKey, string $childKey, array $parentData): array
    {
        $childData = $parentData['childs'][$childKey] ?? [];
        return [
            'title'  => "{$parentKey}.{$childKey}",
            'icon'   => $childData['icon'] ?? '<i class="ti ti-unlink"></i>',
            'url'    => route("admin.{$parentKey}.{$childKey}" , self::getRouteParams()),
            'active' => true,
        ];
    }
}
