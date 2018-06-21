<?php

namespace Admin\Service\Account;

use Admin\Service\Account\Domain\Input as Domain;

class Input
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function save(array $data, int &$id = 0, array &$error = [])
    {
        if ($id) {
            return $this->domain->edit($data, $id, $error);
        }

        return $this->domain->add($data, $id, $error);
    }

    public function edit(array $data, int $id, array &$error = [])
    {
        return $this->domain->edit($data, $id, $error);
    }

    public function updateByIdList(array $data, array $id_list, array &$error = [])
    {
        return $this->domain->updateByIdList($data, $id_list, $error);
    }
}
