<?php

namespace App\Builders\Sidebar;

use App\Builders\BaseBuilder;
use Illuminate\Contracts\View\View;

class SidebarBuilder extends BaseBuilder
{
    /**
     * @var SimpleRouteBuilder
     */
    protected SimpleRouteBuilder $simpleRouteBuilder;

    /**
     * @var DropdownRouteBuilder
     */
    protected DropdownRouteBuilder $dropdownRouteBuilder;

    /**
     * @var GroupRouteBuilder
     */
    protected GroupRouteBuilder $groupRouteBuilder;

    /**
     * SidebarBuilder constructor.
     */
    public function __construct()
    {
        $this->simpleRouteBuilder = new SimpleRouteBuilder();
        $this->dropdownRouteBuilder = new DropdownRouteBuilder();
        $this->groupRouteBuilder = new GroupRouteBuilder();
    }

    /**
     * Build sidebar routes based on configuration
     *
     * @return View Rendered sidebar routes view
     */
    public static function buildFromConfig(): View
    {
        $routeNames = self::getAdminRouteNames();
        $routesConfig = config('sidebar_routes', [])['admin'];
        $groupsConfig = config('sidebar_groups', [])['admin'];

        // Create a new sidebar builder
        $sidebarBuilder = new self();

        // Process each route in the configuration
        foreach ($routesConfig as $key => $route) {
            if (empty($route['group'] ?? null) && empty($route['has_child']) && in_array('admin.'.$key, $routeNames)) {
                $sidebarBuilder->addSimpleRoute($key, $route);
            } elseif (empty($route['group'] ?? null) && !empty($route['has_child'])) {
                $sidebarBuilder->addDropDownRoute($route, $key, $routeNames);
            } elseif (!empty($route['group'] ?? null)) {
                $sidebarBuilder->addGroupRoute($route, $key, $routeNames, $groupsConfig);
            }
        }

        return $sidebarBuilder->render();
    }

    /**
     * Add a simple route to the sidebar
     *
     * @param string $key The route key
     * @param array $route The route configuration
     * @return $this
     */
    public function addSimpleRoute(string $key, array $route): self
    {
        $item = $this->simpleRouteBuilder->build($key, $route);
        return $this->addItem($item, $key);
    }

    /**
     * Add a dropdown route to the sidebar
     *
     * @param array $route The route configuration
     * @param string $key The route key
     * @param array $names Available route names
     * @return $this
     */
    public function addDropDownRoute(array $route, string $key, array $names): self
    {
        $item = $this->dropdownRouteBuilder->build($route, $key, $names);
        if ($item) {
            $this->addItem($item, $key);
        }
        return $this;
    }

    /**
     * Add a group route to the sidebar
     *
     * @param array $route The route configuration
     * @param string $key The route key
     * @param array $names Available route names
     * @param array $groups Group configurations
     * @return $this
     */
    public function addGroupRoute(array $route, string $key, array $names, array $groups): self
    {
        // Get existing children for this group if any
        $items = $this->getItems();
        $existingChildren = $items[$route['group']]['children'] ?? [];

        $item = $this->groupRouteBuilder->build($route, $key, $names, $groups, $existingChildren);
        if ($item) {
            $this->addItem($item, $route['group']);
        }
        return $this;
    }

    /**
     * Get the built sidebar routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->getItems();
    }

    /**
     * Render the sidebar routes view
     *
     * @return View
     */
    public function render(): View
    {
        return view('admin.layouts.parts.sidebar.routes-container', [
            'routes' => $this->getItems(),
        ]);
    }
}
