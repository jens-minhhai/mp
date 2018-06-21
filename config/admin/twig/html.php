<?php

return [
    'css' => function () {
        return new Twig_SimpleFunction('css', function ($target) {
            $asset = '';

            foreach ($target as $src) {
                $asset .= '<link rel="stylesheet" type="text/css" rel="stylesheet" href="' . $src . '" />';
            }

            return $asset;
        }, ['is_safe' => ['html']]);
    },

    'js' => function () {
        return new Twig_SimpleFunction('js', function ($target) {
            $asset = '';

            foreach ($target as $src) {
                $asset .= '<script src="' . $src . '"></script>';
            }

            return $asset;
        }, ['is_safe' => ['html']]);
    },
];
