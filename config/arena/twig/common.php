<?php

return [
    'apply' => function () {
        return new Twig_SimpleFunction('apply', function ($func, ...$args) {
            return call_user_func_array($func, $args);
        });
    },
    'error' => function () {
        return new Twig_SimpleFunction('error', function (string $name, array $error, $first = true) {
            if ($error) {
                $error = array_get($error, $name);

                if ($error && $first) {
                    return array_first($error);
                }
            }

            return $error;
        });
    },
    'msg' => function () {
        return new Twig_SimpleFunction('msg', function ($code, $default) {
            return App::msg($code, $default);
        });
    },
];
