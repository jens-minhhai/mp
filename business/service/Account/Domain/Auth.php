<?php

namespace Service\Account\Domain;

use Service\Account\Repository\Auth as Repository;
use Kernel\Base\Domain\Read;

class Auth extends Read
{
    protected $attribute = [
        'id',
        'email',
        'fullname'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
}
