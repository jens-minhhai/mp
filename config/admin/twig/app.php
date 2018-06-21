<?php

return [
    'mode' => function () {
        return new Twig_SimpleFunction('mode', function ($mode, $path) {
            $master = Config::read($path, []);

            return $master[$mode] ?? '';
        });
    },
    'locale_code' => function () {
        return new Twig_SimpleFunction('locale_code', function () {
            return Request::get('locale_code', 'en');
        });
    },
];
