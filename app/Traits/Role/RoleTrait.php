<?php
namespace App\Traits\Role;
use App\Traits\RouteTrait;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
trait RoleTrait
{
    use RouteTrait;
    public static function translateRouteName(string $fullRouteName): string
{
    $key    = Str::after($fullRouteName, 'admin.');
    $exact = "admin/routes.{$key}";
    if (Lang::has($exact)) {
        $val = Lang::get($exact);
        if (is_string($val)) {
            return $val;
        }
    }
    return __('admin/routes.' . $key. '.index');
}

    public static function getAdminRoutesGrouped(): array
    {
        $except = self::exceptedRoutesFromRoles();
        $all = collect(Route::getRoutes())
            ->filter(fn($route) => $route->getName() !== null)
            ->filter(fn($route) => Str::startsWith($route->getName(), 'admin.'))
            ->filter(fn($route) => in_array('auth:admin', $route->gatherMiddleware()))
            ->map(fn($route) => $route->getName())
            ->filter(function(string $fullName) use ($except) {
                $key = Str::after($fullName, 'admin.');
                return ! in_array($key, $except, true);
            })
            ->values();

        $grouped = $all->groupBy(fn(string $fullName) => explode(
            '.', Str::after($fullName, 'admin.')
        )[0]);

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
