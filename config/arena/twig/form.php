<?php

return [
    'input' => function () {
        return new Twig_SimpleFunction('form_input', function ($field, $default = '') {
            return Request::input($field, $default);
        });
    }
];
