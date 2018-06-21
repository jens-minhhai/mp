<?php

namespace Service\Auth\Login;

use Service\Auth\Login\Domain\Local as Domain;

class Local
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function login($token)
    {
        return $this->domain->login($token);
    }
}
