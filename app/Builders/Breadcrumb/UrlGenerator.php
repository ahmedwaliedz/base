<?php

namespace App\Builders\Breadcrumb;

use Illuminate\Support\Facades\Route;

class UrlGenerator
{
    /**
     * Generate URL for a parent route
     *
     * @param string $parentKey The parent route key
     * @param string &$title The title reference to update if needed
     * @return string The generated URL
     */
    public function generateParentUrl(string $parentKey, string &$title): string
    {
        if (Route::has("admin.{$parentKey}")) {
            return route("admin.{$parentKey}");
        }

        if (Route::has("admin.{$parentKey}.index")) {
            $title = $parentKey . '.index';
            return route("admin.{$parentKey}.index");
        }

        return '#';
    }

    /**
     * Generate URL for a route key
     *
     * @param string $key The route key
     * @return string The generated URL
     */
    public function generateUrl(string $key): string
    {
        try {
            return route('admin.' . $key);
        } catch (\Exception $e) {
            return '#';
        }
    }

    /**
     * Generate URL for a child route
     *
     * @param string $parentKey The parent route key
     * @param string $childKey The child route key
     * @param array $routeParams The route parameters
     * @return string The generated URL
     */
    public function generateChildUrl(string $parentKey, string $childKey, array $routeParams = []): string
    {
        try {
            return route("admin.{$parentKey}.{$childKey}", $routeParams);
        } catch (\Exception $e) {
            return '#';
        }
    }
}
