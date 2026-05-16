<?php

return [
    'admin' => [
        'admin_roles_management' => [
            'title'     => 'admin_roles_management',
            'icon'      => '<i class="ti ti-users-group me-2"></i>',
            'has_child' => true,
        ],
        'content_management' => [
            'title'     => 'content_management',
            'icon'      => '<i class="ti ti-files me-2"></i>',
            'has_child' => true,
        ],
        'locations' => [
            'title'     => 'locations',
            'icon'      => '<i class="ti ti-map-pin me-2"></i>',
            'has_child' => true,
        ],
        'messages_complaints' => [
            'title'     => 'messages_complaints',
            'icon'      => '<i class="ti ti-message-square me-2"></i>',
            'has_child' => true,
        ],
    ]
];
