<?php

return [
    'admin' => [
        'home' => [
            'icon' => '<i class="ti ti-home me-2"></i>',
            'has_child' => false,
        ],
        'settings' => [
            'icon'      => '<i class="ti ti-settings me-2"></i>',
            'has_child' => true,
            'childes'   => [],
        ],
        'notifications' => [
            'icon'      => '<i class="ti ti-mail me-2"></i>',
            'has_child' => true,
            'childes'   => [],
        ],

        'users' => [
            'has_child'    => true,
            'icon'         => '<i class="ti ti-users me-2"></i>',
            'childes'      => [

            ],
        ],
        'admins' => [
            'group'        => 'admin_roles_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-users-group me-2"></i>',
            'childes'      => [
            ],
        ],

        'roles' => [
            'group'        => 'admin_roles_management',
            'has_child'     => true,
            'icon'          => '<i class="ti ti-eye-cog me-2"></i>',
            'childes'       => [

            ],
        ],
    ]
];
