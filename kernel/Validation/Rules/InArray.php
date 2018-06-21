<?php

namespace Kernel\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class InArray extends AbstractRule
{
    protected $array;

    public function __construct($array)
    {
        $this->array = explode(',', $array);
    }

    public function validate($input)
    {
        return in_array($input, $this->array);
    }
}
