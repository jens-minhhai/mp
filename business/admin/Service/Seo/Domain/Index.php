<?php

namespace Admin\Service\Seo\Domain;

use Admin\Service\Seo\Repository\Index as Repository;
use Kernel\Base\Domain\Read;

class Index extends Read
{
    protected $attribute = [
        'id',
        'alias',
        'url',
        'canonical',
        'title',
        'keyword',
        'description',
        'priority',
        'mode',
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
}
