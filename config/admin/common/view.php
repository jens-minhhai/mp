<?php
return [
    'path' => env('APP_VIEW_PATH'),
    // 'cache_html_path' => ROOT . '/' . env('HTML_CACHE_PATH'),
    'cache' => env('APP_VIEW_CACHE'),
    'debug' => env('APP_DEBUG'),
    'auto_reload' => env('APP_VIEW_AUTO_RELOAD'),
    'strict_variables' => env('APP_VIEW_STRICT_MODE'),
    'autoescape' => 'html',
    'optimizations' => -1,
];
