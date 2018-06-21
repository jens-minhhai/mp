<?php

namespace Kernel;

class Factory
{
    private static $chain = [];

    public static function instant(string $name, ...$args)
    {
        $closure = '';
        if ($args) {
            $closure = function () use ($name, $args) {
                $rule = ['shared' => true, 'constructParams' => $args];
                $this->addRule($name, $rule);
            };
        }

        return make($name, $closure);
    }

    public static function singleton($name, ...$args)
    {
        if (self::checkChain($name)) {
            return self::chain($name);
        }

        array_unshift($args, $name);
        $instant = forward_static_call_array(['Kernel\Factory', 'instant'], $args);

        return self::chain($name, $instant);
    }

    public static function adapter($name, ...$args)
    {
        $class = array_shift($args);
        $name .= '\\' . ucfirst($class);
        array_unshift($args, $name);

        return forward_static_call_array(['Kernel\Factory', 'singleton'], $args);
    }

    private static function chain(string $name = '', $obj = null)
    {
        if (is_null($obj)) {
            return self::$chain[$name];
        }

        return self::$chain[$name] = $obj;
    }

    private static function checkChain(string $name = '')
    {
        return array_key_exists($name, self::$chain);
    }
}
