<?php

return [
    'admin' => [
        'home' => [
            'icon' => '<i class="ti ti-home me-2"></i>',
            'has_child' => false,
        ],
        'notifications' => [
            'icon'      => '<i class="ti ti-mail me-2"></i>',
            'has_child' => true,
            'childes'   => [],
        ],
        'settings' => [
            'icon'      => '<i class="ti ti-settings me-2"></i>',
            'has_child' => true,
            'childes'   => [],
        ],
        'admins' => [
            'group'        => 'admin_roles_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-users-group me-2"></i>',
            'childes'      => [
            ],
        ],
        'users' => [
            'group'        => 'admin_roles_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-users me-2"></i>',
            'childes'      => [

            ],
        ],
        'roles' => [
            'has_child'     => true,
            'icon'          => '<i class="ti ti-eye-cog me-2"></i>',
            'childes'       => [

            ],
        ],
    ]
];
