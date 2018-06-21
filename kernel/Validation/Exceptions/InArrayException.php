<?php
namespace Kernel\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class InArrayException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} did not exist.'
        ]
    ];
}
