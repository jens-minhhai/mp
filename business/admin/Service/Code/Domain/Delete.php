<?php

namespace Admin\Service\Code\Domain;

use Admin\Service\Code\Repository\Delete as Repository;
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
