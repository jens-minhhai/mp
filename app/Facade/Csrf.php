<?php

namespace App\Facade;

class Csrf
{
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([container('csrf'), $name], $arguments);
    }
}
