<?php

namespace App\Builders\Breadcrumb;

use App\Traits\Route\RouteTrait;

class ChildCrumbBuilder
{
    use RouteTrait;

    /**
     * @var UrlGenerator
     */
    protected UrlGenerator $urlGenerator;

    /**
     * ChildCrumbBuilder constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Build a child breadcrumb
     *
     * @param string $parentKey The parent route key
     * @param string $childKey The child route key
     * @param array $parentData Parent route data
     * @return array The breadcrumb item
     */
    public function build(string $parentKey, string $childKey, array $parentData): array
    {
        // Use 'childes' consistently as per sidebar_routes.php configuration
        $childrenData = $parentData['childes'][$childKey] ?? [];
        $title = "{$parentKey}.{$childKey}";
        $icon = $childrenData['icon'] ?? '<i class="ti ti-unlink"></i>';
        $url = $this->urlGenerator->generateChildUrl($parentKey, $childKey, self::getRouteParams());

        return [
            'title'  => $title,
            'icon'   => $icon,
            'url'    => $url,
            'active' => true,
        ];
    }
}
