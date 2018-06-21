<?php

namespace Service\Attribute\Text\Domain;

use Service\Attribute\Text\Repository\Delete as Repository;

class Delete
{
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteByTargetIdList(array $target_id, int $target_model)
    {
        return $this->repository->deleteByTargetIdList($target_id, $target_model);
    }
}
