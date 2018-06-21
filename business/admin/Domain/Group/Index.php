<?php

namespace Admin\Domain\Group;

use Admin\Repository\Group\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
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
