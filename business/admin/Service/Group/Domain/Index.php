<?php

namespace Admin\Service\Group\Domain;

use Admin\Service\Group\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'title'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getByIdList(array $id_list)
    {
        return $this->repository->getByIdList($id_list, $this->attribute);
    }
}
