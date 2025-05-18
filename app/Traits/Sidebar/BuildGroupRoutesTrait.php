<?php


namespace App\Traits\Sidebar;


trait BuildGroupRoutesTrait
{
    use BuildSimpleRoutesTrait , BuildDropDownRoutesTrait;
    public static function buildGroupRoute($route , $key , $names , $groups , $routesList): array
    {
        $group = $groups[$route['group']];
        $child = [] ;
        if (empty($route['has_child']) && in_array($key, $names)) {
            $child[] = self::buildSimpleRoute($key , $route);
        }elseif (! empty($route['has_child'])) {
            if (!empty(array_values(self::buildDropDownRoute($route, $key, $names)))) {
                $child[] =  array_values(self::buildDropDownRoute($route, $key, $names))[0];
            }
        }

        $existsChildrens = $routesList[$route['group']]['children'] ?? [] ;
        $childes = array_merge($existsChildrens , $child) ;


        $out[$route['group']] = [
            'is_active'    => array_reduce($childes, function ($carry, $item) {
                return $carry || $item['is_active'];
            }, false),
            'title'        => $group['title'] ?? $route['group'],
            'icon'         => $group['icon']  ?? '',
            'route'        => null,
            'children'     => $childes,
        ];
        return $out ;

    }
}
