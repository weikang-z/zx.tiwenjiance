<?php

return [
    'autoload' => false,
    'hooks' => [
        'app_init' => [
            'epay',
            'qrcode',
        ],
        'admin_login_init' => [
            'loginbg',
        ],
        'config_init' => [
            'nkeditor',
        ],
    ],
    'route' => [
        '/qrcode$' => 'qrcode/index/index',
        '/qrcode/build$' => 'qrcode/index/build',
    ],
    'priority' => [],
];
