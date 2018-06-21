<?php

namespace Admin\Service\Attribute\Dictionary\Domain;

use Admin\Service\Attribute\Dictionary\Repository\Delete as Repository;

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
