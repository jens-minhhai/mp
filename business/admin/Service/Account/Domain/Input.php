<?php

namespace Admin\Service\Account\Domain;

use Admin\Service\Account\Repository\Input as Repository;
use Kernel\Base\Domain\Write;

class Input extends Write
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
}
