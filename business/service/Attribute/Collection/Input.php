<?php

namespace Service\Attribute\Collection;

use Service\Attribute\Collection\Domain\Input as Domain;

class Input
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function save(array $data = [], int $target_id, int $target_model, array &$error = [])
    {
        return $this->domain->saveAttr($data, $target_id, $target_model, $error);
    }
}
