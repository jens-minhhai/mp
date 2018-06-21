<?php

namespace App\Facade;

class Factory
{
    public static function import($name, ...$args)
    {
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function kernel($name, ...$args)
    {
        $name = 'kernel.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function load($name, ...$args)
    {
        $name = Request::get('channel_code') . '.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function helper($name, ...$args)
    {
        $name = Request::get('channel_code') . '.helper.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function service($name, ...$args)
    {
        $name = Request::get('channel_code') . '.service.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function global_service($name, ...$args)
    {
        $name = 'service.' . $name;
        array_unshift($args, namespace_case($name));

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    public static function __callStatic($name, $arguments)
    {
        return forward_static_call_array(['Kernel\Factory', $name], $arguments);
    }
}
