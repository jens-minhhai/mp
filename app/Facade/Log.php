<?php

namespace App\Facade;

class Log
{
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([container('log'), $name], $arguments);
    }
}
