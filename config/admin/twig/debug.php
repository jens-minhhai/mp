<?php

return [
    'print_r' => function () {
        return new Twig_SimpleFunction('print_r', function (... $args) {
            ob_start();

            foreach ($args as $arg) {
                print_r('<pre>');
                print_r($arg);
                print_r('</pre>');
            }

            return ob_get_clean();
        }, ['is_safe' => ['html']]);
    }
];
