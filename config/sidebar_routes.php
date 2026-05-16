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
        'countries' => [
            'has_child' => true,
            'icon'      => '<i class="ti ti-world me-2"></i>',
            'childes'   => [],
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

        // Content Management
        'pages' => [
            'group'        => 'content_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-file-text me-2"></i>',
            'childes'      => [],
        ],
        'sliders' => [
            'group'        => 'content_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-slideshow me-2"></i>',
            'childes'      => [],
        ],
        'faqs' => [
            'group'        => 'content_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-help-circle me-2"></i>',
            'childes'      => [],
        ],
        'posts' => [
            'group'        => 'content_management',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-news me-2"></i>',
            'childes'      => [],
        ],

        // Categories
        'categories' => [
            'has_child' => true,
            'icon'      => '<i class="ti ti-category me-2"></i>',
            'childes'   => [],
        ],

        // Intro Pages
        'intro-pages' => [
            'has_child' => true,
            'icon'      => '<i class="ti ti-layout-sidebar me-2"></i>',
            'childes'   => [],
        ],

        // SEO
        'seo' => [
            'has_child' => true,
            'icon'      => '<i class="ti ti-search me-2"></i>',
            'childes'   => [],
        ],

        // Social Links
        'socials' => [
            'has_child' => true,
            'icon'      => '<i class="ti ti-share me-2"></i>',
            'childes'   => [],
        ],

        // Locations
        'regions' => [
            'group'        => 'locations',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-map me-2"></i>',
            'childes'      => [],
        ],
        'cities' => [
            'group'        => 'locations',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-building me-2"></i>',
            'childes'      => [],
        ],
        'districts' => [
            'group'        => 'locations',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-location me-2"></i>',
            'childes'      => [],
        ],

        // Messages & Complaints
        'contact-messages' => [
            'group'        => 'messages_complaints',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-message-dots me-2"></i>',
            'childes'      => [],
        ],
        'complaints' => [
            'group'        => 'messages_complaints',
            'has_child'    => true,
            'icon'         => '<i class="ti ti-exclamation-circle me-2"></i>',
            'childes'      => [],
        ],
    ]
];
