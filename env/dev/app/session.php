<?php
return [
    'native' => [
        'lifetime' => '1200',
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httponly' => false,
        'name' => time(),
        'autorefresh' => false,
        'save_path' => ROOT . '/' . env('APP_SESSION_PATH')
    ],
];
