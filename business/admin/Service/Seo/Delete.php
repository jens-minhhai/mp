<?php

namespace Admin\Service\Seo;

use Admin\Service\Seo\Domain\Delete as Domain;

class Delete
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function deleteByTargetId(array $target_id, int $target_model)
    {
        return $this->domain->deleteByTargetId($target_id, $target_model);
    }
}
