<?php

return [
    'users' => [
        'parser' => [
            'email' => env('PARSER_EMAIL', 'parser@gmail.com'),
        ],

        'admin' => [
            'email' => env('ADMIN_EMAIL', 'admin@gmail.com'),
        ],
    ]
];
