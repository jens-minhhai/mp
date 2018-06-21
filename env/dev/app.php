<?php

$env = load('app/slim.php', ENV);

$files = [
    'db',
    'log',
    'session',
    'tracy'
];

foreach ($files as $file) {
    $env = array_merge($env, [$file => load("app/{$file}.php", ENV)]);
}

return $env;
