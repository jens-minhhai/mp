<?php

namespace Admin\Service\Auth\Domain;

use Admin\Service\Auth\Repository\Validation as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'account_id'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function target(int $auth_id)
    {
        return $this->repository->target($auth_id, $this->attribute);
    }
}
