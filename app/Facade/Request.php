<?php

namespace App\Facade;

class Request
{
    public static function is(string $method = 'post')
    {
        $target = array_get(container('app'), 'request.method', null);

        return strtolower($target) == $method;
    }

    public static function server(string $name)
    {
        return container('request')->getServerParams()[$name] ?? '';
    }

    public static function input($key = null, $default = null)
    {
        if (empty($key)) {
            return self::get('input', $default);
        }

        return self::get('input.' . $key, $default);
    }

    public static function query(string $detail = '', $default = null)
    {
        if ($detail) {
            $detail = '.' . $detail;
        }

        return array_get(container('app'), 'request.query' . $detail, $default);
    }

    public static function param(string $detail = '', $default = null)
    {
        if ($detail) {
            $detail = '.' . $detail;
        }

        return array_get(container('app'), 'request.name' . $detail, $default);
    }

    public static function all()
    {
        return container('app')['request'];
    }

    public static function get(string $detail = '', $default = null)
    {
        return array_get(container('app'), 'request.' . $detail, $default);
    }
}
