<?php

namespace Service\Attribute\Text;

use Service\Attribute\Text\Domain\Delete as Domain;

class Delete
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function deleteByTargetIdList(array $target_id, int $target_model)
    {
        return $this->domain->deleteByTargetIdList($target_id, $target_model);
    }
}
