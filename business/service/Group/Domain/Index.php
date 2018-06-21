<?php

namespace Service\Group\Domain;

use Service\Group\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'title'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
}
