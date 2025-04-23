<?php


namespace App\Traits\Breadcrumb;


trait BuildSimpleCrumbTrait
{
    protected static function buildSimpleCrumb(bool $active , $key ): array
    {
        $config = config('admin_routes.'.$key);
        return [
            'title'  => $key ,
            'icon'   => $config['icon'] ?? '',
            'url'    => route('admin.'.$key),
            'active' => $active,
        ];
    }
}
