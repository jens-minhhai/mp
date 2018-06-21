<?php

namespace Service\Group\Domain;

use Service\Group\Repository\Auth as Repository;
use Kernel\Base\Domain\Read;

class Auth extends Read
{
    protected $attribute = [
        'id',
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
}
