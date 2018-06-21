<?php

namespace Admin\Service\Account\Domain;

use Admin\Service\Account\Repository\Validation as Repository;
use Factory;
use Kernel\Base\Domain\Read;

class Validation extends Read
{
    protected $attribute = [
        'id',
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function verifyEmail(string $email, int $auth_id)
    {
        $auth_domain = Factory::service('auth.domain.index');
        $auth = $auth_domain->target($auth_id);
        $account_id = $auth['account_id'] ?? 0;

        $account = $this->repository->getByEmail($email, $account_id, $this->attribute);

        return count($account) == 0;
    }
}
