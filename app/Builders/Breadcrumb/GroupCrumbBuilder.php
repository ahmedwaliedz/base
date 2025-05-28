<?php

namespace App\Builders\Breadcrumb;

class GroupCrumbBuilder
{
    /**
     * @var UrlGenerator
     */
    protected UrlGenerator $urlGenerator;

    /**
     * GroupCrumbBuilder constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Build a group breadcrumb
     *
     * @param string $groupKey The group key
     * @param array $routesList List of routes
     * @return array The breadcrumb item
     */
    public function build(string $groupKey = '', array $routesList = []): array
    {
        $group = $routesList[$groupKey] ?? [];
        $title = $groupKey . '.index';
        $icon = $group['icon'] ?? '';

        return [
            'title'  => $title,
            'url'    => '#',
            'icon'   => $icon,
            'active' => false,
        ];
    }
}
