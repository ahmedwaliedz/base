<?php
use Illuminate\Support\Facades\Session;

if (!function_exists('adminLang')) {
    function adminLang() : ?string
    {
        return Session::get('admin-lang') ?? defaultLang();
    }
}

if (!function_exists('adminDirection')) {
    function adminDirection() : ?string
    {
        return Session::get('admin-lang') == 'ar' ? 'rtl' : 'ltr' ;
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

if (!function_exists('getProjectName')) {
    function getProjectName() : string
    {
        if (app()->getLocale() == 'ar') {
            return 'مشروع';
        }else{
            return 'Project';
        }
    }
}


if (!function_exists('currentRouteNameWithoutAdmin')) {
    function currentRouteNameWithoutAdmin() : string
    {
        $currentRouteName = request()->route()->getName();
        return __('admin/routes.'.str_replace('admin.', '', $currentRouteName));
    }
}
