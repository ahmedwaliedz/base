<?php

namespace App\Builders\Breadcrumb;

use App\Builders\BaseBuilder;
use Illuminate\Support\Facades\Route;

class BreadcrumbBuilder extends BaseBuilder
{
    /**
     * @var UrlGenerator
     */
    protected UrlGenerator $urlGenerator;

    /**
     * @var SimpleCrumbBuilder
     */
    protected SimpleCrumbBuilder $simpleCrumbBuilder;

    /**
     * @var ParentCrumbBuilder
     */
    protected ParentCrumbBuilder $parentCrumbBuilder;

    /**
     * @var ChildCrumbBuilder
     */
    protected ChildCrumbBuilder $childCrumbBuilder;

    /**
     * @var GroupCrumbBuilder
     */
    protected GroupCrumbBuilder $groupCrumbBuilder;

    /**
     * BreadcrumbBuilder constructor.
     */
    public function __construct()
    {
        $this->urlGenerator = new UrlGenerator();
        $this->simpleCrumbBuilder = new SimpleCrumbBuilder($this->urlGenerator);
        $this->parentCrumbBuilder = new ParentCrumbBuilder($this->urlGenerator);
        $this->childCrumbBuilder = new ChildCrumbBuilder($this->urlGenerator);
        $this->groupCrumbBuilder = new GroupCrumbBuilder($this->urlGenerator);
    }
    /**
     * Build breadcrumbs based on the current route and sidebar configuration
     *
     * @return string Rendered breadcrumbs HTML
     */
    public static function buildFromConfig(): string
    {
        $routeParts = self::getRouteParts(route: Route::currentRouteName());
        $sidebarRoutes = config('sidebar_routes')['admin'] ?? [];
        $parentKey = $routeParts[0] ?? null;

        // Create a new breadcrumb builder and add home crumb
        $breadcrumbBuilder = new self();
        $breadcrumbBuilder->addSimpleCrumb(routesList: $sidebarRoutes);

        // Return early if we're on the home page or parent route doesn't exist
        if (self::isHome($routeParts) || !isset($sidebarRoutes[$parentKey])) {
            return $breadcrumbBuilder->render();
        }

        $parentRouteData = $sidebarRoutes[$parentKey];

        // Handle simple routes (routes without children)
        if (!$parentRouteData['has_child']) {
            return self::handleSimpleRoute($breadcrumbBuilder, $parentKey);
        }

        // Process complex routes
        // Add group breadcrumb if the route belongs to a group
        if (!empty($parentRouteData['group'])) {
            $breadcrumbBuilder->addGroupCrumb(
                groupKey: $parentRouteData['group'],
                routesList: $sidebarRoutes
            );
        }

        // Handle index or child routes
        $childKey = $routeParts[1] ?? null;
        if ($childKey === 'index') {
            self::handleIndexRoute($breadcrumbBuilder, $parentKey, $parentRouteData);
        } else {
            self::handleChildRoute($breadcrumbBuilder, $parentKey, $childKey, $parentRouteData);
        }

        return $breadcrumbBuilder->render();
    }

    /**
     * Handle simple route breadcrumb (routes without children)
     *
     * @param BreadcrumbBuilder $builder The breadcrumb builder
     * @param string $parentKey The parent route key
     * @return string Rendered breadcrumbs HTML
     */
    protected static function handleSimpleRoute(BreadcrumbBuilder $builder, string $parentKey): string
    {
        $builder->addSimpleCrumb(active: true, key: $parentKey);
        return $builder->render();
    }

    /**
     * Handle index route breadcrumb
     *
     * @param BreadcrumbBuilder $builder The breadcrumb builder
     * @param string $parentKey The parent route key
     * @param array $parentRouteData The parent route data
     * @return void
     */
    protected static function handleIndexRoute(
        BreadcrumbBuilder $builder,
        string $parentKey,
        array $parentRouteData
    ): void {
        $builder->addParentCrumb(
            parentKey: "{$parentKey}.index",
            parentData: [
                'icon' => $parentRouteData['icon'] ?? '',
                'childes' => [], // Using 'childes' to be consistent with sidebar_routes.php
            ],
            active: true
        );
    }

    /**
     * Handle child route breadcrumb
     *
     * @param BreadcrumbBuilder $builder The breadcrumb builder
     * @param string $parentKey The parent route key
     * @param string $childKey The child route key
     * @param array $parentRouteData The parent route data
     * @return void
     */
    protected static function handleChildRoute(
        BreadcrumbBuilder $builder,
        string $parentKey,
        string $childKey,
        array $parentRouteData
    ): void {
        $builder->addParentCrumb(
            parentKey: $parentKey,
            parentData: $parentRouteData,
            active: false
        )->addChildCrumb(
            parentKey: $parentKey,
            childKey: $childKey,
            parentData: $parentRouteData
        );
    }

    /**
     * Add a breadcrumb item
     *
     * @param string $title The breadcrumb title key
     * @param string $url The breadcrumb URL
     * @param string $icon The breadcrumb icon HTML
     * @param bool $active Whether the breadcrumb is active
     * @return $this
     */
    public function addCrumb(string $title, string $url = '#', string $icon = '', bool $active = false): self
    {
        return $this->addItem([
            'title'  => $title,
            'icon'   => $icon,
            'url'    => $url,
            'active' => $active,
        ]);
    }

    /**
     * Add a simple breadcrumb
     *
     * @param bool $active Whether the breadcrumb is active
     * @param string $key The route key
     * @param array $routesList List of routes
     * @return $this
     */
    public function addSimpleCrumb(bool $active = false, string $key = 'home', array $routesList = []): self
    {
        $crumb = $this->simpleCrumbBuilder->build($active, $key, $routesList);
        return $this->addItem($crumb);
    }

    /**
     * Add a parent breadcrumb
     *
     * @param string $parentKey The parent route key
     * @param array $parentData Parent route data
     * @param bool $active Whether the breadcrumb is active
     * @return $this
     */
    public function addParentCrumb(string $parentKey, array $parentData, bool $active): self
    {
        $crumb = $this->parentCrumbBuilder->build($parentKey, $parentData, $active);
        return $this->addItem($crumb);
    }

    /**
     * Add a child breadcrumb
     *
     * @param string $parentKey The parent route key
     * @param string $childKey The child route key
     * @param array $parentData Parent route data
     * @return $this
     */
    public function addChildCrumb(string $parentKey, string $childKey, array $parentData): self
    {
        $crumb = $this->childCrumbBuilder->build($parentKey, $childKey, $parentData);
        return $this->addItem($crumb);
    }

    /**
     * Add a group breadcrumb
     *
     * @param string $groupKey The group key
     * @param array $routesList List of routes
     * @return $this
     */
    public function addGroupCrumb(string $groupKey = '', array $routesList = []): self
    {
        $crumb = $this->groupCrumbBuilder->build($groupKey, $routesList);
        return $this->addItem($crumb);
    }


    /**
     * Get the built breadcrumbs
     *
     * @return array
     */
    public function getCrumbs(): array
    {
        return $this->getItems();
    }

    /**
     * Render the breadcrumbs view
     *
     * @return string
     */
    public function render(): string
    {
        return view('admin.layouts.parts.breadcrumbs', ['crumbs' => $this->getItems()])->render();
    }
}
