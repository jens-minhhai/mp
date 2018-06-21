<?php

namespace Admin\Service\Auth\Domain;

use Admin\Service\Auth\Repository\Validation as Repository;
use Kernel\Base\Domain\Read;

class Validation extends Read
{
    protected $attribute = [
        'id'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function verifyAccount(string $account, int $auth_id)
    {
        $account = $this->repository->getByAccount($account, $auth_id, $this->attribute);

        return count($account) == 0;
    }
}
