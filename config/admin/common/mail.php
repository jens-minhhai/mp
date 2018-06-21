<?php

return [
    'default' => [
        'transport' => 'smtp',
        'host' => 'ssl://smtp.gmail.com',
        'port' => 465,
        'username' => 'vietsolsmtp@gmail.com',
        'password' => 'ngohongd@0',
        'protocol' => 'ssl',
        'charset' => 'utf-8'
    ],
    'local' => [
        'transport' => 'mail',
        'host' => 'localhost',
        'post' => 25,
        'charset' => 'utf-8'
    ],
];
