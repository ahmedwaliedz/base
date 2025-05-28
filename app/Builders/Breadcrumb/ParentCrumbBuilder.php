<?php

namespace App\Builders\Breadcrumb;

class ParentCrumbBuilder
{
    /**
     * @var UrlGenerator
     */
    protected UrlGenerator $urlGenerator;

    /**
     * ParentCrumbBuilder constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Build a parent breadcrumb
     *
     * @param string $parentKey The parent route key
     * @param array $parentData Parent route data
     * @param bool $active Whether the breadcrumb is active
     * @return array The breadcrumb item
     */
    public function build(string $parentKey, array $parentData, bool $active): array
    {
        $title = $parentKey;
        $url = $this->urlGenerator->generateParentUrl($parentKey, $title);
        $icon = $parentData['icon'] ?? '';

        return [
            'title'  => $title,
            'icon'   => $icon,
            'url'    => $url,
            'active' => $active,
        ];
    }
}
