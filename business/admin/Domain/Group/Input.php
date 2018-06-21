<?php

namespace Admin\Domain\Group;

use Admin\Repository\Group\Input as Repository;
use Kernel\Base\Domain\Write;

class Input extends Write
{
    protected $attribute = [
        'id',
        'title',
        'mode'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
