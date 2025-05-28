<?php

namespace App\Builders\Breadcrumb;

class SimpleCrumbBuilder
{
    /**
     * @var UrlGenerator
     */
    protected UrlGenerator $urlGenerator;

    /**
     * SimpleCrumbBuilder constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Build a simple breadcrumb
     *
     * @param bool $active Whether the breadcrumb is active
     * @param string $key The route key
     * @param array $routesList List of routes
     * @return array The breadcrumb item
     */
    public function build(bool $active = false, string $key = 'home', array $routesList = []): array
    {
        $icon = '';
        if (isset($routesList[$key]) && !empty($routesList[$key]['icon'])) {
            $icon = $routesList[$key]['icon'];
        }

        $url = $this->urlGenerator->generateUrl($key);

        return [
            'title'  => $key,
            'icon'   => $icon,
            'url'    => $url,
            'active' => $active,
        ];
    }
}
