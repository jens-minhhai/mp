<?php

namespace Admin\Service\Account;

use Admin\Service\Account\Domain\Index as Domain;

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

    public function getByIdList(array $id_list = [])
    {
        return $this->domain->getByIdList($id_list);
    }
}
