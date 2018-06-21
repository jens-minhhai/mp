<?php

namespace App\Facade;

use Config;
use Kernel\Factory;
use Request;

class View
{
    public static function string(string $string = '', array $params = [])
    {
        extract(Config::read('view'));
        $channel = Request::get('channel_code');
        if (!$option['debug'] && $option['cache']) {
            $option['cache'] = ROOT . '/' . $option['cache'] . $channel . '/';
        }

        return Factory::singleton('Kernel\View')->string($string, $params, $extension);
    }

    public static function fs(string $template = '', array $params = [])
    {
        extract(Config::read('view'));

        $channel = Request::get('channel_code');
        $option['path'] = ROOT . '/' . $option['path'] . $channel . '/';
        if (!$option['debug'] && $option['cache']) {
            $option['cache'] = ROOT . '/' . $option['cache'] . $channel . '/';
        }

        return Factory::singleton('Kernel\View')
                            ->fs($option, $extension)
                            ->render($template, $params);
    }
}
