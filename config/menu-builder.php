<?php

return [
    'view_path' => base_path() . DIRECTORY_SEPARATOR . 'softbd/menu-builder/src' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'menu-builder',
    'template' => [
        'master_page' => 'root-view::core.main',
        'content_placeholer' => 'content',
        'css_placeholder' => 'css',
        'js_placeholder' => 'js',
    ],
    'route' => [
        'prefix' => 'menu-builder',
        'name_prefix' => 'menu-builder.',
        'middleware' => ['web']
    ],
    'menu-disk' => [
        'driver' => 'local',
        'root' => base_path('menu-backup'),
        'permissions' => [
            'file' => [
                'public' => 0664,
                'private' => 0600,
            ],
            'dir' => [
                'public' => 0775,
                'private' => 0700,
            ],
        ],
    ]
];
