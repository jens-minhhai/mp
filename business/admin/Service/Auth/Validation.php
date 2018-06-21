<?php

namespace Admin\Service\Auth;

use Admin\Service\Auth\Domain\Validation as Domain;

class Validation
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function verifyAccount(string $account, int $auth_id)
    {
        return $this->domain->verifyAccount($account, $auth_id);
    }
}
