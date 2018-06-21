<?php

namespace Terminal\Facade;

class Factory
{
    public static function load($name, ...$args)
    {
        $name = 'console.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function kernel($name, ...$args)
    {
        $name = 'kernel.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function __callStatic($name, $arguments)
    {
        return forward_static_call_array(['Kernel\Factory', $name], $arguments);
    }
}
