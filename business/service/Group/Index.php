<?php

namespace Service\Group;

use Service\Group\Domain\Index as Domain;

class Index
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function get()
    {
        return $this->domain->get();
    }
}
