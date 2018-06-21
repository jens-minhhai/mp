<?php

namespace Admin\Service\Account\Domain;

use Admin\Service\Account\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'fullname',
        'email',
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
