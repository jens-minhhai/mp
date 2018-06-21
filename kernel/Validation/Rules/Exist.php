<?php

namespace Kernel\Validation\Rules;

use Factory;
use Respect\Validation\Rules\AbstractRule;

class Exist extends AbstractRule
{
    protected $trigger;
    protected $func;
    protected $params = [];

    public function __construct(string $trigger, string $func, ...$params)
    {
        $this->trigger = Factory::load($trigger);
        $this->func = $func;
        $this->params = $params;
    }

    public function validate($input)
    {
        $func = $this->func;

        return $this->trigger->$func($input, $this->params[0]);
    }
}
