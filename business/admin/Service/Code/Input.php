<?php

namespace Admin\Service\Code;

use Admin\Service\Code\Domain\Input as Domain;

class Input
{
    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }

    public function save(array $data = [], int $target_id, int $target_model, int &$code_id = 0, array &$error = [])
    {
        $data = array_merge($data, compact('target_id', 'target_model'));

        return $this->domain->save($data, $code_id, $error);
    }

    public function updateByTargetIdList(array $data = [], array $target_id = [], int $target_model, array &$error = [])
    {
        return $this->domain->updateByTargetIdList($data, $target_id, $target_model);
    }
}
