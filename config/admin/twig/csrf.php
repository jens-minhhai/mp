<?php

return [
    'csrf' => function () {
        return new Twig_SimpleFunction('form_crsf', function () {
            return "<input type='hidden' name='_token' value='" . Csrf::active() . "' />";
        }, ['is_safe' => ['html']]);
    },
    'active' => function () {
        return new Twig_SimpleFunction('csrf_active', function () {
            return Csrf::active();
        }, ['is_safe' => ['html']]);
    },
];
