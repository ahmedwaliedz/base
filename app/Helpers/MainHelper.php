<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

if (!function_exists('adminLang')) {
    function adminLang() : ?string
    {
        return Session::get('admin-lang') ?? defaultLang();
    }
}

if (!function_exists('adminDirection')) {
    function adminDirection() : ?string
    {
        return adminLang() == 'ar' ? 'rtl' : 'ltr' ;
    }
}

if (!function_exists('settings')) {
    function settings(?string $key = null, mixed $default = null): mixed
    {
        $settings = cache()->get('settings');

        if ($settings instanceof \Illuminate\Support\Collection) {
            $settings = $settings->toArray();
        }

        if (! is_array($settings)) {
            // Cache miss or cleared cache: reload settings from the database.
            $settings = loadSettingsFromDatabase();
        }

        if ($key === null) {
            return $settings;
        }

        return data_get($settings, $key, $default);
    }
}

if (!function_exists('loadSettingsFromDatabase')) {
    function loadSettingsFromDatabase(): array
    {
        $settings = Setting::query()
            ->get()
            ->pluck('value', 'key')
            ->toArray();

        cache()->forever('settings', $settings);

        return $settings;
    }
}

// defaultLang

if (!function_exists('defaultLang')) {
    function defaultLang() : string
    {
        return 'ar';
    }
}

if (!function_exists('languages')) {
    function languages() : array
    {
        return ['ar', 'en'];
    }
}

if (!function_exists('adminRouteLabel')) {
    function adminRouteLabel(string $routeKey) : string
    {
        $translationKey = "admin/routes.admin.{$routeKey}";
        $translated = Lang::get($translationKey);

        if (is_string($translated)) {
            return $translated;
        }

        if (is_array($translated) && isset($translated['index']) && is_string($translated['index'])) {
            return $translated['index'];
        }

        $fallback = Lang::get("{$translationKey}.index");

        return is_string($fallback) ? $fallback : Str::headline($routeKey);
    }
}

if (!function_exists('currentRouteNameWithoutAdmin')) {
    function currentRouteNameWithoutAdmin() : string
    {
        $currentRouteName = request()->route()?->getName();

        if (!$currentRouteName) {
            return '';
        }

        $routeKey = str_replace('admin.', '', $currentRouteName);
        return adminRouteLabel($routeKey);
    }
}
