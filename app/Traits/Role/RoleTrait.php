<?php
namespace App\Traits\Role;
use App\Traits\Route\RouteTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

trait RoleTrait
{
    use RouteTrait;
    public static function translateRouteName(string $fullRouteName): string
{
    $key    = Str::after($fullRouteName, 'admin.');
    $exact = "admin/routes.admin.{$key}";
    if (Lang::has($exact)) {
        $val = Lang::get($exact);
        if (is_string($val)) {
            return $val;
        }
    }
    return __('admin/routes.admin.' . $key. '.index');
}

    public static function getAdminRoutesGrouped(): array
    {
        $all = self::getAllRouteListFromFile();
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
