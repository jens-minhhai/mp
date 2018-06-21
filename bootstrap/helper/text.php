<?php

use Kernel\Lib\Str;

function camel_case(string $string = '')
{
    return Str::camel($string);
}

function kebab_case(string $string = '')
{
    return Str::kebab($string);
}

function lower_case(string $string = '')
{
    return Str::lower($string);
}

function namespace_case(string $string = '', $delimiters = '.')
{
    return str_replace($delimiters, '\\', ucwords($string, '.'));
}

function snake_case(string $string = '')
{
    return Str::snake($string);
}

function str_ascii(string $string = '')
{
    return Str::ascii($string);
}

function str_limit(string $string = '', int $length = 8, $end = '...')
{
    if (mb_strlen($string) <= $length) {
        return $string;
    }

    return mb_substr($string, 0, $length) . $end;
}

function str_random(int $length = 8)
{
    return Str::random($length);
}

function str_slug(string $string = '', string $separator = '-')
{
    return strtolower(Str::slug($string, $separator));
}

function str_unique()
{
    return dechex(time());
}

function studly_case(string $string = '')
{
    return Str::studly($string);
}

function title_case(string $string = '')
{
    return Str::title($string);
}

function upper_case(string $string = '')
{
    return Str::upper($string);
}
