<?php

namespace Admin\Service\Group;

use Admin\Service\Group\Domain\Index as Domain;

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

    public function getByIdList(array $id_list = [])
    {
        return $this->domain->getByIdList($id_list);
    }
}
