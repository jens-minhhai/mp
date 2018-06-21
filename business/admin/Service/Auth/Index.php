<?php

namespace Admin\Service\Auth;

use Admin\Service\Auth\Domain\Validation as Domain;

class Index
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function target(int $auth_id)
    {
        return $this->domain->target($auth_id);
    }
}
