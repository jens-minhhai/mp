<?php

namespace Service\Account;

use Service\Account\Domain\Auth as Domain;

class Auth
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function target($id)
    {
        return $this->domain->target($id);
    }
}
