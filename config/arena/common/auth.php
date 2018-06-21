<?php

return [
    'account' => [
        'facebook' => [
            'id' => '412907082442576',
            'token' => 'cc046e608ab785a7b722f2d1d837f8d9',
            'redirect' => 'auth/facebook',
            'fallback' => 'auth/login'
        ],
        'google' => [
            'id' => '362484266275-7grpg871dqu3r7k521qeco6e28i55cc4.apps.googleusercontent.com',
            'token' => '1UJ1kf93zTlgCgyymqMbBdBe',
            'redirect' => 'auth/google',
            'accessType' => 'offline',
            'fallback' => 'auth/login'
        ],
    ],
    'fallback' => [
        'auth' => [
            'auth' => [
                'login' => true
            ]
        ],
        'token' => [
            'get' => true,
        ]
    ]
];
