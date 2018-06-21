<?php

namespace Admin\Service\Seo;

use Admin\Service\Seo\Domain\Index as Domain;

class Index
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
