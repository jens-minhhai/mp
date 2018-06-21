<?php

namespace Service\Seo\Domain;

use Service\Seo\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Lazy extends Read
{
    protected $attribute = [
        'id',
        'alias'
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getByIdList(array $id = array())
    {
        return $this->repository->getByIdList($id, $this->attribute);
    }
}
