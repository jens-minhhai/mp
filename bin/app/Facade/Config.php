<?php

namespace Terminal\Facade;

class Config
{
    public static function read(string $key, $default = null)
    {
        return array_get(container('app'), 'config.' . $key, $default);
    }

    public static function anonymous(string $type, bool $key = true)
    {
        $default = array_get(container('app'), 'config.app.' . $type, []);

        return $key ? array_first_key($default) : array_first($default);
    }

    public static function load(string $name)
    {
        $name = str_replace('.', '/', $name);
        $channel_code = array_get(container('app'), 'request.channel_code', '');

        return load('config/' . $channel_code. '/' . $name . '.php', BIN);
    }
}
