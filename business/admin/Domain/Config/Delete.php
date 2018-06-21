<?php

namespace Admin\Domain\Config;

use Admin\Repository\Config\Delete as Repository;
use Kernel\Base\Domain\Del;

class Delete extends Del
{
    use \Kernel\Traits\Domain\Relation\DeleteTrait;

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
