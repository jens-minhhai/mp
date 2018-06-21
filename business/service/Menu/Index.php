<?php

namespace Service\Menu;

use Service\Menu\Domain\Index as Domain;

class Index
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function getTree($root)
    {
        return $this->domain->getTree($root);
    }
}
