<?php

namespace Kernel;

use Twig_Autoloader as Twig_Autoloader;
use Twig_Loader_Array as Twig_Loader_Array;
use Twig_Loader_Filesystem as Twig_Loader_Filesystem;
use Twig_Environment as Twig_Environment;
use Twig_SimpleFunction as Twig_SimpleFunction;
use Twig_SimpleFilter as Twig_SimpleFilter;
use Twig_Extension_Debug as Twig_Extension_Debug;

class View
{
    public function string(string $string = '', array $params = [], array $config = [], array $extension = [])
    {
        $env = new Twig_Environment(
                    new Twig_Loader_Array(
                        ['string' => $string]
                    ),
                    $config
                );

        if ($extension) {
            foreach ($extension as $key => $items) {
                foreach ($items as $item) {
                    $env->addFunction($item());
                }
            }
        }

        return $env->render('string', $params);
    }

    public function fs(array $config, array $extension = [])
    {
        $loader = new Twig_Loader_Filesystem($config['path']);
        $env = new Twig_Environment($loader, $config);

        if ($config['debug']) {
            $env->addExtension(new \Twig_Extension_Debug());
        }

        if ($extension) {
            foreach ($extension as $key => $items) {
                foreach ($items as $item) {
                    $env->addFunction($item());
                }
            }
        }

        return $env;
    }
}
