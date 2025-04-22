<?php

return [
    'home' => [
        'is_main_route' => true,
        'icon' => '<i class="ti ti-home me-2"></i>',
        'has_child' => false,
    ],
    'clients' => [
        'is_main_route' => true,
        'has_child' => true,
        'icon' => '<i class="ti ti-users me-2"></i>',
        'childs' => [
            'index' => [
                'is_sub_route' => true,
            ],
            'create' => [
                'is_sub_route' => true,
            ],
            'store' => [],
            'show' => [],
            'edit' => [],
            'update' => [],
            'delete' => [],
            'destroy' => [],
        ],
    ],
    'users' => [
        'group'         => 'user_management',
        'is_main_route' => true,
        'has_child' => true,
        'icon' => '<i class="ti ti-users me-2"></i>',
        'childs' => [
            'index' => [
                'is_sub_route' => true,
            ],
            'create' => [
                'is_sub_route' => true,
            ],
            'store' => [],
            'show' => [],
            'edit' => [],
            'update' => [],
            'delete' => [],
            'destroy' => [],
        ],
    ],
    'admins' => [
        'group'         => 'user_management',
        'is_main_route' => true,
        'has_child'     => true,
        'icon'          => '<i class="ti ti-users-group me-2"></i>',
        'childs'        => [
            'index' => [],
            'create' => [],
            'store' => [],
            'show' => [],
            'edit' => [],
            'update' => [],
            'delete' => [],
            'destroy' => [],
        ],
    ],


];

