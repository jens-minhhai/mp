<?php

return [
    'url' => function () {
        return new Twig_SimpleFunction('url', function (string $url) {
            return Utility::url($url);
        });
    },
];
