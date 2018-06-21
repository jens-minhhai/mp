<?php

namespace Console\Repository\App;

use Factory;
use Kernel\Base\Repository\Read;

class Index extends Read
{
    protected $table = 'app';

    public function scope()
    {
        return [
            'alive' => ['alive']
        ];
    }
}
