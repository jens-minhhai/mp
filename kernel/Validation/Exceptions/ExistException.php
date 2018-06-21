<?php
namespace Kernel\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class ExistException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} has been existed.'
        ]
    ];
}
