<?php

return [
    'check' => function () {
        return new Twig_SimpleFunction('session_check', function ($token, $dafault) {
            return Session::check($token, $dafault);
        }, ['is_safe' => ['html']]);
    },
    'read' => function () {
        return new Twig_SimpleFunction('session_read', function ($token, $default = '') {
            return Session::read();
        }, ['is_safe' => ['html']]);
    }
];
