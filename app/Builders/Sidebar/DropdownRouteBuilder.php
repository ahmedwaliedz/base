<?php

namespace App\Builders\Sidebar;

class DropdownRouteBuilder
{
    use DeterminesSidebarRouteActive;

    /**
     * Build a dropdown route item for the sidebar
     *
     * @param  array  $route  The route configuration
     * @param  string  $key  The route key
     * @param  array  $names  Available route names
     * @return array|null The sidebar item or null if no children
     */
    public function build(array $route, string $key, array $names): ?array
    {
        $children = $this->buildChildren($route, $key, $names);

        if (empty($children)) {
            if (in_array("admin.{$key}.index", $names)) {
                // If no children but index route exists, use SimpleRouteBuilder
                $simpleBuilder = new SimpleRouteBuilder;

                return $simpleBuilder->build("{$key}.index", $route);
            }

            return null;
        }

        // Return dropdown with children
        return [
            'is_active' => $this->areAnyChildrenActive($children),
            'title' => $route['title'] ?? $key,
            'icon' => $route['icon'] ?? '',
            'route' => "{$key}.index",
            'children' => $children,
        ];
    }

    /**
     * Build children items for a dropdown
     *
     * @param  array  $route  The route configuration
     * @param  string  $key  The route key
     * @param  array  $names  Available route names
     * @return array The children items
     */
    private function buildChildren(array $route, string $key, array $names): array
    {
        $children = [];
        foreach ($route['childes'] as $act => $opts) {
            $full = "{$key}.{$act}";
            if (in_array('admin.'.$full, $names) && ! empty($opts['is_sub_route'])) {
                $children[] = [
                    'is_active' => $this->isRouteActive($full),
                    'title' => $opts['title'] ?? $act,
                    'icon' => $opts['icon'] ?? '',
                    'route' => $full,
                    'children' => [],
                ];
            }
        }

        return $children;
    }

    /**
     * Check if any children are active
     *
     * @param  array  $children  The children items
     * @return bool Whether any child is active
     */
    private function areAnyChildrenActive(array $children): bool
    {
        return array_reduce($children, function ($carry, $item) {
            return $carry || $item['is_active'];
        }, false);
    }
}
