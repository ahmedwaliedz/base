<?php

namespace  App\Traits\Breadcrumb;


use App\Traits\RolePermission\RouteTrait;
use Illuminate\Support\Facades\Route;

trait BreadcrumbTrait
{
    use RouteTrait , BuildSimpleCrumbTrait , BuildParentCrumbTrait , BuildChildCrumbTrait , BuildGroupCrumbTrait;

    public static function buildBreadcrumbsFromConfig()
    {
        $parts = self::getRouteParts();
        $routes = config('admin_routes');
        $groups = config('admin_groups');
        $parentKey  = $parts[0] ?? null;
        $parentData = $routes[$parentKey] ?? [];
        $crumbs[] = self::buildSimpleCrumb(false , 'home');

        // if current route is home page
        if (self::isHome($parts)) {
            return self::render($crumbs);
        }
        // simple routes
        if ( isset($routes[$parts[0]]) && $routes[$parts[0]]['has_child'] == false ) {
            $crumbs[] = self::buildSimpleCrumb(true , $parentKey);
            return self::render($crumbs);
        }
        // Case check that route have group and get group data
        if (!empty($parentData['group'])) {
            $crumbs[] = self::buildGroupCrumb($parentData['group']);
        }

        if ($parts[1] === 'index') {
            $crumbs[] = self::buildParentCrumb("{$parentKey}.index", [
                'icon' => $parentData['icon'] ?? '',
                'childs' => [],
            ], true);
        } else {
            // Case 4: Sub-route under parent
            // first, parent crumb (non-active)
            $crumbs[] = self::buildParentCrumb($parentKey, $parentData, false);
            // then child crumb (active)
            $crumbs[] = self::buildChildCrumb($parentKey, $parts[1], $parentData);
        }
        return self::render($crumbs);
    }



    /**
     * Render the breadcrumbs via Blade
     */
    protected static function render(array $crumbs): string
    {
        return view('admin.layouts.parts.breadcrumbs', compact('crumbs'))->render();
    }
}
