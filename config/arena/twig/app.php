<?php

return [
    'mode' => function () {
        return new Twig_SimpleFunction('mode', function ($mode, $path) {
            $master = Config::read($path, []);

            return $master[$mode] ?? '';
        });
    }
];
