<?php

namespace Service\Code\Domain;

use Service\Code\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'code',
        'target_id'
    ];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    public function getByTargetId(int $target_id, int $target_model)
    {
        return $this->repository->getByTargetId($target_id, $target_model, $this->attribute);
    }

    public function getByTargetIdList(array $target_id_list, int $target_model)
    {
        return $this->repository->getByTargetIdList($target_id_list, $target_model, $this->attribute);
    }

    public function getByCode(string $code, int $target_model)
    {
        return $this->repository->getByCode($code, $target_model, $this->attribute);
    }
}
