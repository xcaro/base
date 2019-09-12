<?php

return [
    'defaults' => [
        'guard'     => 'token'
    ],
    'guards'   => [
        'token' => [
            'driver'   => 'access_token',
            'provider' => 'users',
            'hash'     => false,
        ],
    ],
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => Si6\Base\User::class,
        ],
    ],
];
