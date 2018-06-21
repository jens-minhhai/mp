<?php

namespace Service\Attribute\Collection\Domain;

use Service\Attribute\Collection\Repository\Delete as Repository;

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
