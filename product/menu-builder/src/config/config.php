<?php

return [
    'template' => [
        'content_placeholder' => 'content',
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
