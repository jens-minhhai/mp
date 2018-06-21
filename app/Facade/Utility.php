<?php

namespace App\Facade;

class Utility
{
    public static function url($path = '')
    {
        return Request::get('base_url') . '/' . $path;
    }
}
