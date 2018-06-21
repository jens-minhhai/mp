<?php

namespace App\Facade;

class Session
{
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([container('session'), $name], $arguments);
    }
}
