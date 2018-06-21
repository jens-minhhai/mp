<?php

namespace App\Facade;

use Kernel\Lazy\Load;

class Lazy
{
    public static function assign(array $collection, array $criteria, array $name = [])
    {
        return container('lazy')->assign($collection, $criteria, $name);
    }

    public static function load(array $data = [])
    {
        return container('lazy')->load($data);
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([container('lazy'), $name], $arguments);
    }
}
