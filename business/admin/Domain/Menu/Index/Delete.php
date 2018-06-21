<?php

namespace Admin\Domain\Menu\Index;

use Kernel\Base\Domain\Del;
use Admin\Repository\Menu\Index\Delete as Repository;

class Delete extends Del
{
    use \Kernel\Traits\Domain\Relation\DeleteTrait;

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }
}
