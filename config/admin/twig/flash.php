<?php

return [
    'read' => function () {
        return new Twig_SimpleFunction('flash_read', function ($key, $dafault = null, $remove = true) {
            return Flash::read($key, $dafault, $remove);
        }, ['is_safe' => ['html']]);
    },
    'check' => function () {
        return new Twig_SimpleFunction('flash_check', function ($key) {
            return Flash::check($key);
        }, ['is_safe' => ['html']]);
    },
];
