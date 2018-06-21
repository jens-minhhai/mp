<?php

namespace Service\Attribute\Text;

use Service\Attribute\Text\Domain\Index as Domain;

class Index
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function getByTargetId(int $target_id, int $target_model)
    {
        return $this->domain->getByTargetId($target_id, $target_model);
    }

    public function getByTargetIdList(array $target_id_list, int $target_model)
    {
        return $this->domain->getByTargetIdList($target_id_list, $target_model);
    }
}
