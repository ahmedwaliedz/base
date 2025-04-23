<?php

namespace App\Traits\Breadcrumb;

use Illuminate\Support\Facades\Route;

trait BuildParentCrumbTrait
{
    protected static function buildParentCrumb(string $parentKey, array $parentData, bool $active): array
    {
        $title = $parentKey;
        if (Route::has("admin.{$parentKey}")) {
            $url = route("admin.{$parentKey}");
        } elseif (Route::has("admin.{$parentKey}.index")) {
            $title = $parentKey . '.index';
            $url = route("admin.{$parentKey}.index");
        } else {
            dd(2);
        }

        return [
            'title'  => $title,
            'icon'   => $parentData['icon'] ?? '',
            'url'    => $url,
            'active' => $active,
        ];

    }

}
