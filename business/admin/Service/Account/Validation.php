<?php

namespace Admin\Service\Account;

use Admin\Service\Account\Domain\Validation as Domain;

class Validation
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function verifyEmail(string $email, int $auth_id)
    {
        return $this->domain->verifyEmail($email, $auth_id);
    }
}
