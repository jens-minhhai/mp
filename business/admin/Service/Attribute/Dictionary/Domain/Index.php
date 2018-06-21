<?php

namespace Admin\Service\Attribute\Dictionary\Domain;

use Admin\Service\Attribute\Dictionary\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'name',
        'value',
        'target_id'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getByTargetId(int $target_id, int $target_model)
    {
        return $this->repository->getByTargetId($target_id, $target_model, $this->attribute);
    }

    public function getByTargetIdList(array $target_id_list, int $target_model)
    {
        return $this->repository->getByTargetIdList($target_id_list, $target_model, $this->attribute);
    }
}
