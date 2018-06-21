<?php
    return [
        'account' => [
            'required',
            'exist|service.auth.validation@verifyAccount'
        ],
        'mode' => [
            'required',
            'integer',
            'min.numeric|1',
        ],
        'fullname' => [
            'required',
        ],
        'email' => [
            'required',
            'email',
            'exist|service.account.validation@verifyEmail'
        ]
    ];
