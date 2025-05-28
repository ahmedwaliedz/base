<?php

namespace App\Builders\Sidebar;

class GroupRouteBuilder
{
    /**
     * Build a group route item for the sidebar
     *
     * @param array $route The route configuration
     * @param string $key The route key
     * @param array $names Available route names
     * @param array $groups Group configurations
     * @param array $existingChildren Existing children for this group
     * @return array|null The sidebar item or null if no children
     */
    public function build(array $route, string $key, array $names, array $groups, array $existingChildren = []): ?array
    {
        $group = $groups[$route['group']];
        $child = $this->buildChild($route, $key, $names);

        if (empty($child) && empty($existingChildren)) {
            return null;
        }

        $children = array_merge($existingChildren, $child ? [$child] : []);

        return [
            'is_active' => $this->areAnyChildrenActive($children),
            'title'     => $group['title'] ?? $route['group'],
            'icon'      => $group['icon'] ?? '',
            'route'     => null,
            'children'  => $children,
        ];
    }

    /**
     * Build a child item for a group
     *
     * @param array $route The route configuration
     * @param string $key The route key
     * @param array $names Available route names
     * @return array|null The child item or null
     */
    private function buildChild(array $route, string $key, array $names): ?array
    {
        if (empty($route['has_child']) && in_array('admin.'.$key, $names)) {
            // Simple route
            $simpleBuilder = new SimpleRouteBuilder();
            return $simpleBuilder->build($key, $route);
        } elseif (!empty($route['has_child'])) {
            // Dropdown route
            $dropdownBuilder = new DropdownRouteBuilder();
            return $dropdownBuilder->build($route, $key, $names);
        }

        return null;
    }

    /**
     * Check if any children are active
     *
     * @param array $children The children items
     * @return bool Whether any child is active
     */
    private function areAnyChildrenActive(array $children): bool
    {
        return array_reduce($children, function ($carry, $item) {
            return $carry || $item['is_active'];
        }, false);
    }
}
