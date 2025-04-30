<?php
namespace App\Traits\Role;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
trait RoleTrait
{
    public static function translateRouteName(string $fullRouteName): string
{
    // strip "admin." → "roles" or "roles.create"
    $key    = Str::after($fullRouteName, 'admin.');
    $parent = Str::before($key, '.');

    // 1) exact action match: admin/routes.php → ['roles']['create']
    $exact = "admin/routes.{$key}";
    if (Lang::has($exact)) {
        $val = Lang::get($exact);
        if (is_string($val)) {
            return $val;
        }
    }

    // 2) parent.index fallback: admin/routes.php → ['roles']['index']
    $idxKey = "admin/routes.{$parent}.index";
    if (Lang::has($idxKey)) {
        $val = Lang::get($idxKey);
        if (is_string($val)) {
            return $val;
        }
    }

    // 3) parent-level lookup: admin/routes.php → ['roles'] => array
    $parentKey = "admin/routes.{$parent}";
    if (Lang::has($parentKey)) {
        $val = Lang::get($parentKey);
        // if it’s a string, return it
        if (is_string($val)) {
            return $val;
        }
        // if it’s an array with an 'index' entry, return that
        if (is_array($val) && isset($val['index'])) {
            return $val['index'];
        }
    }

    // 4) last resort: humanize "roles.create" → "Roles Create"
    return Str::title(str_replace(['.', '_'], ' ', $key));
}


    /**
     * Return all admin.* routes that live under the auth:admin group,
     * grouped by the first segment after "admin.", each with name+label.
     *
     * @return array<string, array<int, array{name:string,label:string}>>
     */
    public static function getAdminRoutesGrouped(): array
    {
        // pull your exception list once
        $except = exceptedRoutes(); // e.g. ['logout','lang.change', ...]

        // 1) collect all routes, filter by name, middleware, AND excepted
        $all = collect(Route::getRoutes())
            ->filter(fn($route) => $route->getName() !== null)
            ->filter(fn($route) => Str::startsWith($route->getName(), 'admin.'))
            ->filter(fn($route) => in_array('auth:admin', $route->gatherMiddleware()))
            ->map(fn($route) => $route->getName())
            ->filter(function(string $fullName) use ($except) {
                // strip "admin." → "roles.index", "logout", etc.
                $key = Str::after($fullName, 'admin.');
                return ! in_array($key, $except, true);
            })
            ->values();

        // 2) group by the first segment after "admin."
        $grouped = $all->groupBy(fn(string $fullName) => explode(
            '.', Str::after($fullName, 'admin.')
        )[0]);

        // 3) map each group into ['name'=>..., 'label'=>...]
        return $grouped
            ->mapWithKeys(function (Collection $names, string $groupKey) {
                return [
                    $groupKey => $names
                        ->map(fn(string $fullName) => [
                            'name'  => $fullName,
                            'label' => static::translateRouteName($fullName),
                        ])
                        ->values()
                        ->all(),
                ];
            })
            ->toArray();
    }

}
