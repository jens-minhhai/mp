<?php

namespace App\Facade;

class Flash
{
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([container('flash'), $name], $arguments);
    }
}
