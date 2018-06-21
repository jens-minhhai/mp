<?php

namespace Terminal\Facade;

class App
{
    public static function translate(string $code, $default = null)
    {
        $locale = array_get(container('app'), 'msg', []);

        return $locale[$code] ?? $default;
    }

    public static function validate(array $data, array $rules, array &$error = [])
    {
        $validator = Factory::kernel('validation.validator');

        if ($validator->assert($data, $rules)) {
            return true;
        }

        $error = $validator->error();

        return false;
    }
}
