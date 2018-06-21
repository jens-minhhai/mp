<?php

namespace Admin\Service\Account\Domain;

use Admin\Service\Account\Repository\Delete as Repository;
use Kernel\Base\Domain\Del;

class Delete extends Del
{
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteByTargetId(array $target_id, int $target_model)
    {
        return $this->repository->deleteByTargetId($target_id, $target_model);
    }
}
