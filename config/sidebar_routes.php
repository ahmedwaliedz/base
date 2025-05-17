<?php

return [
    'home' => [
        'icon' => '<i class="ti ti-home me-2"></i>',
        'has_child' => false,
    ],
    'notifications' => [
        'icon' => '<i class="ti ti-mail me-2"></i>',
        'has_child' => true,
        'childs'        => [],
    ],
    'settings' => [
        'icon' => '<i class="ti ti-settings me-2"></i>',
        'has_child' => true,
        'childs'        => [],
    ],
    'roles' => [
        'has_child'     => true,
        'icon'          => '<i class="ti ti-eye-cog me-2"></i>',
        'childs'        => [],
    ],

    'admins' => [
        'group'        => 'admin_roles_management',
        'has_child'     => true,
        'icon'          => '<i class="ti ti-users-group me-2"></i>',
        'childs'        => [],
    ],
    'users' => [
        'group'        => 'admin_roles_management',
        'has_child'     => true,
        'icon'          => '<i class="ti ti-users me-2"></i>',
        'childs'        => [],
    ],


];

