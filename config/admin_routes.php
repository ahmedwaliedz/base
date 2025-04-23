<?php

return [
    'home' => [
        'is_main_route' => true,
        'icon' => '<i class="ti ti-home me-2"></i>',
        'has_child' => false,
    ],
    'notifications' => [
        'is_main_route' => true,
        'icon' => '<i class="ti ti-mail me-2"></i>',
        'has_child' => false,
    ],
    'roles' => [
        'group'         => 'admin_roles_management',
        'is_main_route' => true,
        'has_child'     => true,
        'icon'          => '<i class="ti ti-eye-cog me-2"></i>',
        'childs'        => [],
    ],

    'admins' => [
        'group'         => 'admin_roles_management',
        'is_main_route' => true,
        'has_child'     => true,
        'icon'          => '<i class="ti ti-users-group me-2"></i>',
        'childs'        => [
            'create' => [
                'is_sub_route' => true,
            ],
            'index' => [
                'is_sub_route' => true,
            ],
        ],
    ],
    'users' => [
        'is_main_route' => true,
        'has_child'     => true,
        'icon'          => '<i class="ti ti-users me-2"></i>',
        'childs'        => [],
    ],


];

