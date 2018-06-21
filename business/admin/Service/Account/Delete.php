<?php

namespace Admin\Service\Account;

use Admin\Service\Account\Domain\Delete as Domain;

class Delete
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function delete(array $id_list)
    {
        return $this->domain->delete($id_list);
    }
}
